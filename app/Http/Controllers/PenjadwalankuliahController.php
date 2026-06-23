<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class PenjadwalankuliahController extends Controller
{
    // === Cache properties untuk menghindari N+1 query ===
    private $cacheLoaded = false;
    private $cacheKelasMatkulById = [];
    private $cacheMatkulByKodeAndTahun = [];
    private $cacheMatkulByKode = [];
    private $cacheRuangByKode = [];
    private $cacheAllRuang = null;
    private $cacheHariByKode = [];
    private $cacheAllDosenKodes = [];
    private $cacheBlocking = null;
    private $cacheProdiByKode = [];
    private $cacheDosenListByIdKelas = [];
    private $cacheKodeHariAktif = null;
    private $cacheRuangAlternatif = [];
    private $cacheDosenByKode = [];
    private $cacheAllDosenObjects = null;
    private $cacheMatkulDosen = [];
    // Legacy properties (kept for compatibility; waktu/jam tables removed)
    private $cacheWaktuByKode = [];
    private $cacheJamByKode = [];
    private $cacheWaktuJoinJam = null;
    private $cacheWaktuValidBySksHari = [];

    // Dynamic scheduling settings
    private $jamMulaiSetting = '07:00';
    private $jamTerakhirSetting = '17:00';
    private $durasiSksSetting = 50;
    private $jedaSetting = 10;
    private $istirahatMulaiSetting = '12:00';
    private $istirahatSelesaiSetting = '13:00';

    private function preloadReferenceData()
    {
        if ($this->cacheLoaded) {
            return;
        }

        foreach (DB::table('kelas_matkul')->get() as $km) {
            $this->cacheKelasMatkulById[$km->id_kelas] = $km;
        }

        foreach (DB::table('matkul')->get() as $m) {
            $key = $m->kode_matkul . '|' . ($m->tahun_ajaran ?? '');
            $this->cacheMatkulByKodeAndTahun[$key] = $m;
            if (!isset($this->cacheMatkulByKode[$m->kode_matkul])) {
                $this->cacheMatkulByKode[$m->kode_matkul] = $m;
            }
        }

        $this->cacheAllRuang = DB::table('ruang')->orderBy('kode_ruang')->get();
        foreach ($this->cacheAllRuang as $r) {
            $this->cacheRuangByKode[$r->kode_ruang] = $r;
        }





        foreach (DB::table('hari')->get() as $h) {
            $this->cacheHariByKode[$h->kode_hari] = $h;
        }



        $allDosen = DB::table('dosen')->orderBy('kode_dosen')->get();
        $this->cacheAllDosenObjects = $allDosen;
        foreach ($allDosen as $d) {
            $this->cacheAllDosenKodes[] = $d->kode_dosen;
            $this->cacheDosenByKode[$d->kode_dosen] = $d;
        }
        $this->cacheAllDosenKodes = array_values(array_unique(array_filter($this->cacheAllDosenKodes)));

        $this->cacheBlocking = DB::table('blocking_jadwal_dosen')->get();

        foreach (DB::table('prodi')->get() as $p) {
            $this->cacheProdiByKode[$p->kode_prodi] = $p;
        }

        // Preload relasi matkul_dosen (dosen pengampu tetap)
        foreach (DB::table('matkul_dosen')->get() as $md) {
            $key = $md->kode_matkul . '|' . $md->tahun_ajaran;
            if (!isset($this->cacheMatkulDosen[$key])) {
                $this->cacheMatkulDosen[$key] = [];
            }
            $this->cacheMatkulDosen[$key][] = $md->kode_dosen;
        }

        $this->cacheLoaded = true;
    }

    private function getCachedKelasMatkul($idKelas)
    {
        return $this->cacheKelasMatkulById[$idKelas] ?? null;
    }

    private function getCachedMatkul($kodeMatkul, $tahunAjaran = null)
    {
        if ($tahunAjaran) {
            $key = $kodeMatkul . '|' . $tahunAjaran;
            if (isset($this->cacheMatkulByKodeAndTahun[$key])) {
                return $this->cacheMatkulByKodeAndTahun[$key];
            }
        }
        return $this->cacheMatkulByKode[$kodeMatkul] ?? null;
    }

    private function getCachedRuang($kodeRuang)
    {
        return $this->cacheRuangByKode[$kodeRuang] ?? null;
    }

    private function getCachedWaktu($kodeWaktu)
    {
        return $this->cacheWaktuByKode[$kodeWaktu] ?? null;
    }

    private function getCachedHari($kodeHari)
    {
        return $this->cacheHariByKode[$kodeHari] ?? null;
    }

    private function getCachedJam($kodeJam)
    {
        return $this->cacheJamByKode[$kodeJam] ?? null;
    }

    private function getCachedProdiNama($kodeProdi)
    {
        $p = $this->cacheProdiByKode[$kodeProdi] ?? null;
        return $p ? $p->nama_prodi : null;
    }

    private function pilihDuaDosenAcak(array $kodeDosenList, int $limit = 2)
    {
        $kodeDosenList = array_values(array_unique(array_filter($kodeDosenList)));

        if (count($kodeDosenList) <= $limit) {
            return $kodeDosenList;
        }

        shuffle($kodeDosenList);

        return array_slice($kodeDosenList, 0, $limit);
    }

    private function getKodeDosenListByIdKelas($idKelas)
    {
        if (isset($this->cacheDosenListByIdKelas[$idKelas])) {
            return $this->cacheDosenListByIdKelas[$idKelas];
        }

        $kelasMatkul = $this->cacheLoaded
            ? $this->getCachedKelasMatkul($idKelas)
            : DB::table('kelas_matkul')->where('id_kelas', $idKelas)->first();

        if (!$kelasMatkul) {
            $result = $this->pilihDosenOtomatisGlobal([], 2);
            $this->cacheDosenListByIdKelas[$idKelas] = $result;
            return $result;
        }

        $matkul = $this->cacheLoaded
            ? $this->getCachedMatkul($kelasMatkul->kode_matkul, $kelasMatkul->tahun_ajaran)
            : DB::table('matkul')->where('kode_matkul', $kelasMatkul->kode_matkul)->where('tahun_ajaran', $kelasMatkul->tahun_ajaran)->first();

        if (!$matkul) {
            $matkul = $this->cacheLoaded
                ? $this->getCachedMatkul($kelasMatkul->kode_matkul)
                : DB::table('matkul')->where('kode_matkul', $kelasMatkul->kode_matkul)->first();
        }

        // CEK DOSEN PENGAMPU TETAP dari tabel matkul_dosen
        $keyPengampu = $kelasMatkul->kode_matkul . '|' . ($kelasMatkul->tahun_ajaran ?? '');
        $dosenPengampu = $this->cacheMatkulDosen[$keyPengampu] ?? [];

        if (count($dosenPengampu) >= 1) {
            // Ambil semua kelas untuk matkul ini pada tahun ajaran dan semester yang sama, urutkan berdasarkan nama_kelas
            $allClassesForMatkul = DB::table('kelas_matkul')
                ->where('kode_matkul', $kelasMatkul->kode_matkul)
                ->where('tahun_ajaran', $kelasMatkul->tahun_ajaran)
                ->where('kode_semester', $kelasMatkul->kode_semester)
                ->orderBy('nama_kelas')
                ->get();

            $totalClasses = $allClassesForMatkul->count();
            $currentClassIndex = 0;
            foreach ($allClassesForMatkul as $index => $c) {
                if ($c->id_kelas == $kelasMatkul->id_kelas) {
                    $currentClassIndex = $index;
                    break;
                }
            }

            $dosenCount = count($dosenPengampu);

            // Jika jumlah dosen pengampu > 2 dan cukup untuk dibagikan ke kelas-kelas (dosenCount >= totalClasses),
            // maka distribusikan secara merata.
            if ($dosenCount > 2 && $dosenCount >= $totalClasses && $totalClasses > 1) {
                $base = (int) floor($dosenCount / $totalClasses);
                $remainder = $dosenCount % $totalClasses;

                $offset = 0;
                for ($i = 0; $i < $currentClassIndex; $i++) {
                    $offset += $base + ($i < $remainder ? 1 : 0);
                }
                $limit = $base + ($currentClassIndex < $remainder ? 1 : 0);

                $result = array_slice($dosenPengampu, $offset, $limit);
            } else {
                // Sebaliknya (dosenCount <= 2 atau jumlah dosen lebih sedikit dari jumlah kelas),
                // berikan semua dosen pengampu untuk semua kelas.
                $result = array_values(array_unique(array_filter($dosenPengampu)));
            }

            $this->cacheDosenListByIdKelas[$idKelas] = $result;
            return $result;
        }

        // Tidak ada dosen pengampu yang di-set manual, fallback ke logika otomatis
        $kodeProdi = $kelasMatkul->kode_prodi ?? ($matkul->kode_prodi ?? null);
        $candidate = $this->getDosenCandidateByKodeProdi($kodeProdi);

        if (count($candidate) < 2) {
            $candidate = $this->getDosenCandidateGlobal();
        }

        if (count($candidate) < 2) {
            $this->cacheDosenListByIdKelas[$idKelas] = [];
            return [];
        }

        // Tidak ada dosen pengampu manual: pilih 2 dosen secara acak dari kandidat.
        $result = $this->pilihDuaDosenAcak($candidate, 2);

        $this->cacheDosenListByIdKelas[$idKelas] = $result;
        return $result;
    }

    private function getDosenCandidateByKodeProdi($kodeProdi = null)
    {
        if ($this->cacheLoaded) {
            if (!$kodeProdi) {
                return $this->cacheAllDosenKodes;
            }

            $namaProdi = $this->getCachedProdiNama($kodeProdi);

            if (!$namaProdi) {
                return count($this->cacheAllDosenKodes) >= 2 ? $this->cacheAllDosenKodes : [];
            }

            $hasil = [];
            // FIX: Gunakan cache dosen, bukan query DB lagi
            foreach ($this->cacheAllDosenObjects ?? [] as $d) {
                $ps = strtolower($d->program_studi ?? '');
                if ($ps == strtolower($namaProdi) || strpos($ps, strtolower($namaProdi)) !== false) {
                    $hasil[] = $d->kode_dosen;
                }
            }
            $hasil = array_values(array_unique(array_filter($hasil)));

            return count($hasil) >= 2 ? $hasil : $this->cacheAllDosenKodes;
        }

        $query = DB::table('dosen');

        if ($kodeProdi) {
            $namaProdi = DB::table('prodi')
                ->where('kode_prodi', $kodeProdi)
                ->value('nama_prodi');

            if ($namaProdi) {
                $query->where(function ($q) use ($namaProdi) {
                    $q->whereRaw('LOWER(program_studi) = ?', [strtolower($namaProdi)])
                      ->orWhereRaw('LOWER(program_studi) LIKE ?', ['%' . strtolower($namaProdi) . '%']);
                });
            }
        }

        $hasil = $query
            ->orderBy('kode_dosen')
            ->pluck('kode_dosen')
            ->toArray();

        $hasil = array_values(array_unique(array_filter($hasil)));

        if (count($hasil) < 2) {
            return $this->getDosenCandidateGlobal();
        }

        return $hasil;
    }

    private function getDosenCandidateGlobal()
    {
        if ($this->cacheLoaded) {
            return $this->cacheAllDosenKodes;
        }

        return DB::table('dosen')
            ->orderBy('kode_dosen')
            ->pluck('kode_dosen')
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }

    private function getNamaDosenGabungan(array $kodeDosenList)
    {
        if (count($kodeDosenList) == 0) {
            return '-';
        }

        // FIX: Gunakan cache dosen untuk menghindari query DB per panggilan
        if ($this->cacheLoaded && count($this->cacheDosenByKode) > 0) {
            $names = [];
            foreach ($kodeDosenList as $kode) {
                if (isset($this->cacheDosenByKode[$kode])) {
                    $names[] = $this->cacheDosenByKode[$kode]->nama;
                }
            }
            return count($names) > 0 ? implode(', ', $names) : '-';
        }

        return DB::table('dosen')
            ->whereIn('kode_dosen', $kodeDosenList)
            ->pluck('nama')
            ->implode(', ');
    }

    private function isDosenBentrok(array $dosenA, array $dosenB)
    {
        return count(array_intersect($dosenA, $dosenB)) > 0;
    }

    private function getBatasJamSelesaiKuliah()
    {
        // Batas ini berarti jadwal yang selesai di atas 17:15 tidak akan dipakai.
        // Jika ingin lebih ketat, ubah menjadi '17:00'.
        return '17:15';
    }

    /**
     * Batas jam untuk slot "pagi". Mata kuliah 3 SKS akan diprioritaskan
     * mendapat slot yang jam mulai-nya <= batas ini.
     */
    private function getBatasJamPagi()
    {
        return '10:00';
    }

    private function isSlotPagi($jamMulai)
    {
        return $this->jamToMinutes($jamMulai) <= $this->jamToMinutes($this->getBatasJamPagi());
    }

    private function jamToMinutes($jam)
    {
        if (!$jam) {
            return 0;
        }

        $jam = substr((string) $jam, 0, 5);
        $parts = explode(':', $jam);

        $hour = isset($parts[0]) ? (int) $parts[0] : 0;
        $minute = isset($parts[1]) ? (int) $parts[1] : 0;

        return ($hour * 60) + $minute;
    }

    private function minutesToJam($minutes)
    {
        return str_pad(floor($minutes / 60), 2, '0', STR_PAD_LEFT)
            . ':'
            . str_pad($minutes % 60, 2, '0', STR_PAD_LEFT);
    }

    private function hitungJamSelesai($jamMulai, $jumlahSks)
    {
        $menitDalamSks = ((int) $jumlahSks) * 50;
        $totalMenit = $this->jamToMinutes($jamMulai) + $menitDalamSks;

        return $this->minutesToJam($totalMenit);
    }

    private function isJamSelesaiValid($jamMulai, $jumlahSks)
    {
        return $this->jamToMinutes($jamMulai) <= $this->jamToMinutes($this->jamTerakhirSetting);
    }

    private function getJumlahSksByKelasMatkul($kelasMatkul)
    {
        if (!$kelasMatkul) {
            return 1;
        }

        if ($this->cacheLoaded) {
            $matkul = $this->getCachedMatkul($kelasMatkul->kode_matkul, $kelasMatkul->tahun_ajaran);
            if (!$matkul) {
                $matkul = $this->getCachedMatkul($kelasMatkul->kode_matkul);
            }
            return $matkul && $matkul->sks ? (int) $matkul->sks : 1;
        }

        $jumlahSks = DB::table('matkul')
            ->where('kode_matkul', $kelasMatkul->kode_matkul)
            ->where('tahun_ajaran', $kelasMatkul->tahun_ajaran)
            ->value('sks');

        if (!$jumlahSks) {
            $jumlahSks = DB::table('matkul')
                ->where('kode_matkul', $kelasMatkul->kode_matkul)
                ->value('sks');
        }

        return $jumlahSks ? (int) $jumlahSks : 1;
    }

    /**
     * Menentukan jenis mata kuliah (teori/praktikum) berdasarkan kelas matkul.
     * Digunakan untuk mencocokkan ruang: praktikum → laboratorium, teori → reguler.
     */
    private function getJenisMatkulByKelasMatkul($kelasMatkul)
    {
        if (!$kelasMatkul) {
            return 'teori';
        }

        if ($this->cacheLoaded) {
            $matkul = $this->getCachedMatkul($kelasMatkul->kode_matkul, $kelasMatkul->tahun_ajaran);
            if (!$matkul) {
                $matkul = $this->getCachedMatkul($kelasMatkul->kode_matkul);
            }
            return $matkul && isset($matkul->jenis_matkul) ? $matkul->jenis_matkul : 'teori';
        }

        $jenis = DB::table('matkul')
            ->where('kode_matkul', $kelasMatkul->kode_matkul)
            ->where('tahun_ajaran', $kelasMatkul->tahun_ajaran)
            ->value('jenis_matkul');

        if (!$jenis) {
            $jenis = DB::table('matkul')
                ->where('kode_matkul', $kelasMatkul->kode_matkul)
                ->value('jenis_matkul');
        }

        return $jenis ?: 'teori';
    }

    private function getKodeWaktuValidBySks($jumlahSks)
    {
        // waktu/jam tables removed: return valid kode_hari (1-5 for teori, 6-7 for praktikum)
        $cacheKey = 'sks_' . $jumlahSks;
        if (isset($this->cacheWaktuValidBySksHari[$cacheKey])) {
            return $this->cacheWaktuValidBySksHari[$cacheKey];
        }
        $kodeHariAktif = $this->getKodeHariAktifKuliah();
        $result = count($kodeHariAktif) > 0 ? $kodeHariAktif : [1, 2, 3, 4, 5];
        $this->cacheWaktuValidBySksHari[$cacheKey] = $result;
        return $result;
    }

    private function randomKodeWaktuValidBySks($jumlahSks)
    {
        // waktu/jam tables removed: just pick a random valid kode_hari
        $kodeHariAktif = $this->getKodeHariAktifKuliah();
        if (count($kodeHariAktif) == 0) {
            return null;
        }
        return $kodeHariAktif[mt_rand(0, count($kodeHariAktif) - 1)];
    }

    private function getKodeHariAktifKuliah()
    {
        if ($this->cacheKodeHariAktif !== null) {
            return $this->cacheKodeHariAktif;
        }

        // PENTING: database lama kamu campur aduk antara "senin", "jumat", "Jum'at", dan "Jumat".
        // Kalau pakai whereIn case-sensitive, yang kebaca bisa cuma Jumat.
        // Akibatnya semua jadwal numpuk di Jumat.
        $urutanHari = ['senin', 'selasa', 'rabu', 'kamis', 'jumat'];

        if ($this->cacheLoaded && count($this->cacheHariByKode) > 0) {
            $hariRows = array_values($this->cacheHariByKode);
        } else {
            $hariRows = DB::table('hari')
                ->select('kode_hari', 'nama_hari')
                ->get()
                ->toArray();
        }

        $hasil = [];

        foreach ($urutanHari as $hariTarget) {
            foreach ($hariRows as $hari) {
                $namaHari = strtolower(trim((string) $hari->nama_hari));
                $namaHari = str_replace(["'", '`', chr(0xE2).chr(0x80).chr(0x99)], '', $namaHari);

                if ($namaHari == $hariTarget) {
                    $hasil[] = $hari->kode_hari;
                    break;
                }
            }
        }

        $this->cacheKodeHariAktif = $hasil;
        return $hasil;
    }

    private function getKodeWaktuValidBySksAndHari($jumlahSks, $kodeHari = null)
    {
        // waktu/jam tables removed. Returns list of kode_hari values.
        $cacheKey = $jumlahSks . '_' . ($kodeHari ?? 'all');
        if (isset($this->cacheWaktuValidBySksHari[$cacheKey])) {
            return $this->cacheWaktuValidBySksHari[$cacheKey];
        }

        if ($kodeHari !== null) {
            $result = [$kodeHari];
        } else {
            $result = $this->getKodeHariAktifKuliah();
            if (count($result) == 0) {
                $result = [1, 2, 3, 4, 5];
            }
        }

        $this->cacheWaktuValidBySksHari[$cacheKey] = $result;
        return $result;
    }

    private function randomKodeWaktuValidBySksAndHari($jumlahSks, $kodeHari = null)
    {
        $kodeWaktuValid = $this->getKodeWaktuValidBySksAndHari($jumlahSks, $kodeHari);

        if (count($kodeWaktuValid) == 0) {
            if ($kodeHari !== null) {
                if ($kodeHari == 6 || $kodeHari == 7) {
                    $otherDay = ($kodeHari == 6) ? 7 : 6;
                    $kodeWaktuValid = $this->getKodeWaktuValidBySksAndHari($jumlahSks, $otherDay);
                } else {
                    foreach ([1, 2, 3, 4, 5] as $day) {
                        $wValid = $this->getKodeWaktuValidBySksAndHari($jumlahSks, $day);
                        if (count($wValid) > 0) {
                            $kodeWaktuValid = array_merge($kodeWaktuValid, $wValid);
                        }
                    }
                }
            }

            if (count($kodeWaktuValid) == 0) {
                return $this->randomKodeWaktuValidBySks($jumlahSks);
            }
        }

        // waktu/jam tables removed: kodeWaktuValid contains kode_hari values
        return $kodeWaktuValid[mt_rand(0, count($kodeWaktuValid) - 1)];
    }

    private function hitungPenaltySebaranHari(array $jadwalIndividu)
    {
        $kodeHariAktif = $this->getKodeHariAktifKuliah();

        if (count($kodeHariAktif) == 0 || count($jadwalIndividu) == 0) {
            return 0;
        }

        $jumlahPerHari = [];

        foreach ($kodeHariAktif as $kodeHari) {
            $jumlahPerHari[$kodeHari] = 0;
        }

        foreach ($jadwalIndividu as $row) {
            $kodeHari = $row['kode_hari'] ?? null;

            if ($kodeHari !== null && array_key_exists($kodeHari, $jumlahPerHari)) {
                $jumlahPerHari[$kodeHari]++;
            }
        }

        $totalKelas = count($jadwalIndividu);
        $jumlahHari = count($kodeHariAktif);
        $idealPerHari = (int) ceil($totalKelas / $jumlahHari);
        $batasMaksPerHari = max(1, $idealPerHari + 1);
        $penalty = 0;

        foreach ($jumlahPerHari as $totalHari) {
            // Penalty dibuat lebih kuat supaya GA tidak menumpuk di satu hari
            // walaupun override deterministik final sudah dihapus.
            if ($totalHari > $batasMaksPerHari) {
                $penalty += ($totalHari - $batasMaksPerHari) * 15;
            }

            // Hari kosong dipenalti hanya jika jumlah kelas memang cukup untuk mengisi semua hari aktif.
            if ($totalKelas >= $jumlahHari && $totalHari == 0) {
                $penalty += 10;
            }
        }

        return $penalty;
    }

    private function isKodeWaktuValidBySks($kodeWaktu, $jumlahSks)
    {
        // waktu/jam tables removed. kodeWaktu is now treated as kode_hari.
        // Any valid kode_hari is considered a valid "time slot".
        return is_numeric($kodeWaktu) && $kodeWaktu > 0;
    }


    private function isMatkulFisikaDasar($namaMatkul)
    {
        if (!$namaMatkul) {
            return false;
        }
        $lower = strtolower($namaMatkul);
        $hasFisikaDasar = strpos($lower, 'fisika') !== false && strpos($lower, 'dasar') !== false;
        $isPrak = strpos($lower, 'prak') !== false || strpos($lower, 'praktikum') !== false;
        return $hasFisikaDasar && $isPrak;
    }

    private function isRuangFisika($namaRuang)
    {
        if (!$namaRuang) {
            return false;
        }
        $lower = strtolower($namaRuang);
        return (strpos($lower, 'laboratorium fisika') !== false || strpos($lower, 'lab fisika') !== false || strpos($lower, 'lab. fisika') !== false);
    }

    private function getNamaMatkulByKelasMatkul($kelasMatkul)
    {
        if (!$kelasMatkul) {
            return '';
        }

        if ($this->cacheLoaded) {
            $matkul = $this->getCachedMatkul($kelasMatkul->kode_matkul, $kelasMatkul->tahun_ajaran);
            if (!$matkul) {
                $matkul = $this->getCachedMatkul($kelasMatkul->kode_matkul);
            }
            return $matkul && isset($matkul->nama_matkul) ? $matkul->nama_matkul : '';
        }

        $nama = DB::table('matkul')
            ->where('kode_matkul', $kelasMatkul->kode_matkul)
            ->where('tahun_ajaran', $kelasMatkul->tahun_ajaran)
            ->value('nama_matkul');

        if (!$nama) {
            $nama = DB::table('matkul')
                ->where('kode_matkul', $kelasMatkul->kode_matkul)
                ->value('nama_matkul');
        }

        return $nama ?: '';
    }


    private function getRuangAlternatifValid($kodeProdi = null, $jumlahMahasiswa = 0, $jenisMatkul = null, $namaMatkul = null)
    {
        $cacheKey = ($kodeProdi ?? 'null') . '_' . $jumlahMahasiswa . '_' . ($jenisMatkul ?? 'all') . '_' . ($namaMatkul ?? 'all');
        if (isset($this->cacheRuangAlternatif[$cacheKey])) {
            return $this->cacheRuangAlternatif[$cacheKey];
        }

        // Tentukan tipe ruang yang dibutuhkan berdasarkan jenis matkul
        $tipeRuangTarget = null;
        if ($jenisMatkul === 'praktikum') {
            $tipeRuangTarget = 'laboratorium';
        } elseif ($jenisMatkul === 'teori') {
            $tipeRuangTarget = 'reguler';
        }

        if ($this->cacheLoaded && $this->cacheAllRuang) {
            $namaProdi = $kodeProdi ? $this->getCachedProdiNama($kodeProdi) : null;
            $minKapasitas = $jumlahMahasiswa ?: 0;

            // Filter 1: prodi + kapasitas + tipe ruang
            if ($namaProdi) {
                $ruangList = $this->cacheAllRuang->filter(function ($r) use ($namaProdi, $minKapasitas, $tipeRuangTarget) {
                    $matchProdi = $r->nama_prodi == $namaProdi;
                    $matchKapasitas = $r->kapasitas >= $minKapasitas;
                    $matchTipe = $tipeRuangTarget === null || ($r->tipe_ruang ?? 'reguler') === $tipeRuangTarget;
                    return $matchProdi && $matchKapasitas && $matchTipe;
                })->values();
            } else {
                $ruangList = collect();
            }

            // Filter 2: kapasitas + tipe ruang (tanpa prodi)
            if ($ruangList->isEmpty() && $tipeRuangTarget) {
                $ruangList = $this->cacheAllRuang->filter(function ($r) use ($minKapasitas, $tipeRuangTarget) {
                    return $r->kapasitas >= $minKapasitas && ($r->tipe_ruang ?? 'reguler') === $tipeRuangTarget;
                })->values();
            }

            // Filter 3: hanya tipe ruang (tanpa kapasitas)
            if ($ruangList->isEmpty() && $tipeRuangTarget) {
                $ruangList = $this->cacheAllRuang->filter(function ($r) use ($tipeRuangTarget) {
                    return ($r->tipe_ruang ?? 'reguler') === $tipeRuangTarget;
                })->values();
            }

            // Filter 4: kapasitas saja (fallback tanpa tipe)
            if ($ruangList->isEmpty()) {
                $ruangList = $this->cacheAllRuang->filter(function ($r) use ($minKapasitas) {
                    return $r->kapasitas >= $minKapasitas;
                })->values();
            }

            // Filter 5: semua ruang (fallback terakhir)
            if ($ruangList->isEmpty()) {
                $ruangList = $this->cacheAllRuang;
            }
        } else {
            $ruangQuery = DB::table('ruang');

            if ($kodeProdi) {
                $namaProdi = DB::table('prodi')
                    ->where('kode_prodi', $kodeProdi)
                    ->value('nama_prodi');

                if ($namaProdi) {
                    $ruangQuery->where('nama_prodi', $namaProdi);
                }
            }

            if ($tipeRuangTarget) {
                $ruangQuery->where('tipe_ruang', $tipeRuangTarget);
            }

            $ruangList = $ruangQuery
                ->where('kapasitas', '>=', $jumlahMahasiswa ?: 0)
                ->orderBy('kode_ruang')
                ->get();

            // Fallback: hanya tipe ruang (tanpa prodi/kapasitas)
            if (count($ruangList) == 0 && $tipeRuangTarget) {
                $ruangList = DB::table('ruang')
                    ->where('tipe_ruang', $tipeRuangTarget)
                    ->where('kapasitas', '>=', $jumlahMahasiswa ?: 0)
                    ->orderBy('kode_ruang')
                    ->get();
            }

            if (count($ruangList) == 0 && $tipeRuangTarget) {
                $ruangList = DB::table('ruang')
                    ->where('tipe_ruang', $tipeRuangTarget)
                    ->orderBy('kode_ruang')
                    ->get();
            }

            if (count($ruangList) == 0) {
                $ruangList = DB::table('ruang')
                    ->where('kapasitas', '>=', $jumlahMahasiswa ?: 0)
                    ->orderBy('kode_ruang')
                    ->get();
            }

            if (count($ruangList) == 0) {
                $ruangList = DB::table('ruang')
                    ->orderBy('kode_ruang')
                    ->get();
            }
        }

        $isFisikaDasar = $this->isMatkulFisikaDasar($namaMatkul);

        if (!($ruangList instanceof \Illuminate\Support\Collection)) {
            $ruangList = collect($ruangList);
        }

        $ruangList = $ruangList->filter(function ($r) use ($isFisikaDasar) {
            $isFisikaRuang = $this->isRuangFisika($r->nama_ruang);
            if ($isFisikaDasar) {
                return $isFisikaRuang;
            } else {
                return !$isFisikaRuang;
            }
        })->values();

        $this->cacheRuangAlternatif[$cacheKey] = $ruangList;
        return $ruangList;
    }

    private function getWaktuCandidatesByHariAndSks($kodeHari, $jumlahSks)
    {
        $cacheKey = $kodeHari . '_' . $jumlahSks;
        if (isset($this->cacheWaktuValidBySksHari['wc_' . $cacheKey])) {
            return $this->cacheWaktuValidBySksHari['wc_' . $cacheKey];
        }

        $batasMulaiMenit = $this->jamToMinutes($this->jamTerakhirSetting);
        $durasiMenit = $jumlahSks * $this->durasiSksSetting;
        $jedaMenit = $this->jedaSetting;

        $hasil = [];
        $slotMulai = $this->jamToMinutes($this->jamMulaiSetting);
        $slotIdx = 1;

        while ($slotMulai <= $batasMulaiMenit) {
            $jamStr = $this->minutesToJam($slotMulai);
            $selesaiMenit = $slotMulai + $durasiMenit;

            // Skip lunch break
            $istirahatMulaiMinutes = $this->jamToMinutes($this->istirahatMulaiSetting);
            $istirahatSelesaiMinutes = $this->jamToMinutes($this->istirahatSelesaiSetting);

            if ($slotMulai < $istirahatMulaiMinutes && $selesaiMenit > $istirahatMulaiMinutes) {
                $slotMulai = $istirahatSelesaiMinutes;
                continue;
            }
            if ($slotMulai >= $istirahatMulaiMinutes && $slotMulai < $istirahatSelesaiMinutes) {
                $slotMulai = $istirahatSelesaiMinutes;
                continue;
            }

            $slot = (object)[
                'kode_waktu' => $kodeHari * 100 + $slotIdx,
                'kode_hari'  => $kodeHari,
                'kode_jam'   => $slotIdx,
                'jam'        => $jamStr,
            ];
            $hasil[] = $slot;

            $slotMulai += $jedaMenit;
            $slotIdx++;
        }

        $this->cacheWaktuValidBySksHari['wc_' . $cacheKey] = $hasil;
        return $hasil;
    }

    private function isBentrokDenganDraft(array $draft, array $candidate)
    {
        foreach ($draft as $existing) {
            if (($existing['kode_hari'] ?? null) != ($candidate['kode_hari'] ?? null)) {
                continue;
            }

            $mulaiA = $this->jamToMinutes($existing['jam_mulai'] ?? '00:00');
            $selesaiA = $this->jamToMinutes($existing['jam_selesai'] ?? '00:00');
            $mulaiB = $this->jamToMinutes($candidate['jam_mulai'] ?? '00:00');
            $selesaiB = $this->jamToMinutes($candidate['jam_selesai'] ?? '00:00');

            $waktuBentrok = $mulaiA < $selesaiB && $mulaiB < $selesaiA;

            if (!$waktuBentrok) {
                continue;
            }

            $dosenA = $existing['kode_dosen_list'] ?? [];
            $dosenB = $candidate['kode_dosen_list'] ?? [];

            if ($this->isDosenBentrok($dosenA, $dosenB)) {
                return true;
            }

            if (($existing['kode_ruang'] ?? null) && ($existing['kode_ruang'] ?? null) == ($candidate['kode_ruang'] ?? null)) {
                return true;
            }

            if (($existing['kode_rombel'] ?? '-') != '-' && ($existing['kode_rombel'] ?? '-') == ($candidate['kode_rombel'] ?? '-')) {
                return true;
            }
        }

        return false;
    }



    private function normalisasiNamaHari($namaHari)
    {
        $namaHari = strtolower(trim((string) $namaHari));
        return str_replace(["'", '`', chr(0xE2).chr(0x80).chr(0x99)], '', $namaHari);
    }

    private function isCandidateBlockedByDosen(array $kodeDosenList, $kodeHari, $jamMulai, $jamSelesai)
    {
        $hari = $this->cacheLoaded
            ? $this->getCachedHari($kodeHari)
            : DB::table('hari')->where('kode_hari', $kodeHari)->first();

        if (!$hari) {
            return false;
        }

        $namaHari = $this->normalisasiNamaHari($hari->nama_hari);
        $mulaiCandidate = $this->jamToMinutes($jamMulai);
        $selesaiCandidate = $this->jamToMinutes($jamSelesai);
        $blockingRows = $this->cacheLoaded ? $this->cacheBlocking : DB::table('blocking_jadwal_dosen')->get();

        foreach ($blockingRows as $blocking) {
            if (!in_array($blocking->kode_dosen, $kodeDosenList)) {
                continue;
            }

            if ($this->normalisasiNamaHari($blocking->hari) != $namaHari) {
                continue;
            }

            $mulaiBlocking = $this->jamToMinutes(substr($blocking->jam_mulai, 0, 5));
            $selesaiBlocking = $this->jamToMinutes(substr($blocking->jam_selesai, 0, 5));

            if ($mulaiCandidate < $selesaiBlocking && $mulaiBlocking < $selesaiCandidate) {
                return true;
            }
        }

        return false;
    }

    private function hitungSkorCandidateTerhadapDraft(array $draft, array $candidate)
    {
        $score = 0;

        if (($candidate['time_invalid'] ?? 0) == 1) {
            $score += 90000;
        }

        if (($candidate['blocked'] ?? 0) == 1) {
            $score += 85000;
        }

        if (($candidate['capacity_invalid'] ?? 0) == 1) {
            $score += 70000;
        }

        if (($candidate['room_type_mismatch'] ?? 0) == 1) {
            $score += 75000;
        }

        foreach ($draft as $existing) {
            if (($existing['kode_hari'] ?? null) != ($candidate['kode_hari'] ?? null)) {
                continue;
            }

            $mulaiA = $this->jamToMinutes($existing['jam_mulai'] ?? '00:00');
            $selesaiA = $this->jamToMinutes($existing['jam_selesai'] ?? '00:00');
            $mulaiB = $this->jamToMinutes($candidate['jam_mulai'] ?? '00:00');
            $selesaiB = $this->jamToMinutes($candidate['jam_selesai'] ?? '00:00');

            $waktuBentrok = $mulaiA < $selesaiB && $mulaiB < $selesaiA;

            if (!$waktuBentrok) {
                continue;
            }

            $dosenA = $existing['kode_dosen_list'] ?? [];
            $dosenB = $candidate['kode_dosen_list'] ?? [];

            if ($this->isDosenBentrok($dosenA, $dosenB)) {
                $score += 95000;
            }

            if (($existing['kode_ruang'] ?? null) && ($existing['kode_ruang'] ?? null) == ($candidate['kode_ruang'] ?? null)) {
                $score += 90000;
            }

            if (($existing['kode_rombel'] ?? '-') != '-' && ($existing['kode_rombel'] ?? '-') == ($candidate['kode_rombel'] ?? '-')) {
                $score += 90000;
            }
        }

        return $score;
    }

    private function getCandidateBucketKeys($kodeHari, $jamMulai, $jamSelesai, $stepMenit = 15)
    {
        $mulai = $this->jamToMinutes($jamMulai);
        $selesai = $this->jamToMinutes($jamSelesai);

        if ($selesai <= $mulai) {
            return [];
        }

        $awalBucket = (int) floor($mulai / $stepMenit) * $stepMenit;
        $akhirBucket = (int) ceil($selesai / $stepMenit) * $stepMenit;
        $keys = [];

        for ($menit = $awalBucket; $menit < $akhirBucket; $menit += $stepMenit) {
            $keys[] = $kodeHari . '|' . $menit;
        }

        return $keys;
    }

    private function hitungSkorCandidateTerhadapDraftIndex(array $draftIndex, array $candidate)
    {
        $score = 0;

        if (($candidate['time_invalid'] ?? 0) == 1) {
            $score += 90000;
        }

        if (($candidate['blocked'] ?? 0) == 1) {
            $score += 85000;
        }

        if (($candidate['capacity_invalid'] ?? 0) == 1) {
            $score += 70000;
        }

        if (($candidate['room_type_mismatch'] ?? 0) == 1) {
            $score += 75000;
        }

        $bucketKeys = $this->getCandidateBucketKeys(
            $candidate['kode_hari'] ?? null,
            $candidate['jam_mulai'] ?? '00:00',
            $candidate['jam_selesai'] ?? '00:00'
        );

        $jumlahBentrokDosen = 0;
        $jumlahBentrokRuang = 0;
        $jumlahBentrokRombel = 0;

        foreach ($bucketKeys as $bucketKey) {
            foreach (($candidate['kode_dosen_list'] ?? []) as $kodeDosen) {
                $key = $kodeDosen . '|' . $bucketKey;
                if (isset($draftIndex['dosen'][$key])) {
                    $jumlahBentrokDosen++;
                }
            }

            $kodeRuang = $candidate['kode_ruang'] ?? null;
            if ($kodeRuang) {
                $keyRuang = $kodeRuang . '|' . $bucketKey;
                if (isset($draftIndex['ruang'][$keyRuang])) {
                    $jumlahBentrokRuang++;
                }
            }

            $kodeRombel = $candidate['kode_rombel'] ?? '-';
            if ($kodeRombel != '-') {
                $keyRombel = $kodeRombel . '|' . $bucketKey;
                if (isset($draftIndex['rombel'][$keyRombel])) {
                    $jumlahBentrokRombel++;
                }
            }
        }

        if ($jumlahBentrokDosen > 0) {
            $score += 95000 + min($jumlahBentrokDosen, 10) * 2500;
        }

        if ($jumlahBentrokRuang > 0) {
            $score += 90000 + min($jumlahBentrokRuang, 10) * 2000;
        }

        if ($jumlahBentrokRombel > 0) {
            $score += 90000 + min($jumlahBentrokRombel, 10) * 2000;
        }

        return $score;
    }

    private function tambahCandidateKeDraftIndex(array &$draftIndex, array $candidate)
    {
        if (!isset($draftIndex['dosen'])) {
            $draftIndex['dosen'] = [];
        }
        if (!isset($draftIndex['ruang'])) {
            $draftIndex['ruang'] = [];
        }
        if (!isset($draftIndex['rombel'])) {
            $draftIndex['rombel'] = [];
        }

        $bucketKeys = $this->getCandidateBucketKeys(
            $candidate['kode_hari'] ?? null,
            $candidate['jam_mulai'] ?? '00:00',
            $candidate['jam_selesai'] ?? '00:00'
        );

        foreach ($bucketKeys as $bucketKey) {
            foreach (($candidate['kode_dosen_list'] ?? []) as $kodeDosen) {
                $draftIndex['dosen'][$kodeDosen . '|' . $bucketKey] = true;
            }

            $kodeRuang = $candidate['kode_ruang'] ?? null;
            if ($kodeRuang) {
                $draftIndex['ruang'][$kodeRuang . '|' . $bucketKey] = true;
            }

            $kodeRombel = $candidate['kode_rombel'] ?? '-';
            if ($kodeRombel != '-') {
                $draftIndex['rombel'][$kodeRombel . '|' . $bucketKey] = true;
            }
        }
    }

    private function repairIndividuMinBentrok(array $chromosomes, array $prioritasKelas = [], bool $aggressive = false)
    {
        if (count($chromosomes) == 0) {
            return $chromosomes;
        }

        $kodeHariAktif = $this->getKodeHariAktifKuliah();

        if (count($kodeHariAktif) == 0) {
            return $chromosomes;
        }

        $prioritasMap = [];
        foreach ($prioritasKelas as $prioritas) {
            if (isset($prioritas['id_kelas'])) {
                // Support both old kode_waktu-based and new kode_hari-based prioritas
                $prioritasMap[$prioritas['id_kelas']] = $prioritas['kode_hari'] ?? ($prioritas['kode_waktu'] ?? null);
            }
        }

        $hariLoad = [];
        foreach ($kodeHariAktif as $kodeHari) {
            $hariLoad[$kodeHari] = 0;
        }
        $hariLoad[6] = 0;
        $hariLoad[7] = 0;

        $order = array_keys($chromosomes);
        usort($order, function ($a, $b) use ($chromosomes) {
            $kelasA = $this->getCachedKelasMatkul($chromosomes[$a][0] ?? null);
            $kelasB = $this->getCachedKelasMatkul($chromosomes[$b][0] ?? null);
            $sksA = $this->getJumlahSksByKelasMatkul($kelasA);
            $sksB = $this->getJumlahSksByKelasMatkul($kelasB);

            if ($sksA == $sksB) {
                return mt_rand(-1, 1);
            }

            return $sksB <=> $sksA;
        });

        $draft = [];
        $draftIndex = [
            'dosen' => [],
            'ruang' => [],
            'rombel' => [],
        ];
        $hasil = $chromosomes;

        foreach ($order as $idx) {
            $old = $chromosomes[$idx] ?? null;

            if (!$old || !isset($old[0])) {
                continue;
            }

            $idKelas = $old[0];
            $kelasMatkul = $this->getCachedKelasMatkul($idKelas);

            if (!$kelasMatkul) {
                continue;
            }

            $jumlahSks = $this->getJumlahSksByKelasMatkul($kelasMatkul);
            $jumlahMahasiswa = (int) ($kelasMatkul->jumlah_mahasiswa ?? 0);
            $kodeProdi = $kelasMatkul->kode_prodi ?? null;
            $kodeRombel = $kelasMatkul->kode_rombel ?: (($kelasMatkul->kode_matkul ?? '') . ($kelasMatkul->nama_kelas ?? ''));
            $kodeDosenList = $this->getKodeDosenListByIdKelas($idKelas);
            $kodeDosenList = array_values(array_unique(array_filter($kodeDosenList)));

            if (count($kodeDosenList) < 1) {
                $hasil[$idx] = $old;
                continue;
            }

            $jenisMatkul = $this->getJenisMatkulByKelasMatkul($kelasMatkul);
            $namaMatkul = $this->getNamaMatkulByKelasMatkul($kelasMatkul);

            $ruangList = [];
            foreach ($this->getRuangAlternatifValid($kodeProdi, $jumlahMahasiswa, $jenisMatkul, $namaMatkul) as $ruang) {
                $ruangList[] = $ruang;
            }

            if (count($ruangList) == 0) {
                $hasil[$idx] = $old;
                continue;
            }

            shuffle($ruangList);
            usort($ruangList, function ($a, $b) use ($jumlahMahasiswa, $old) {
                $scoreA = ($a->kode_ruang == ($old[1] ?? null)) ? -50 : 0;
                $scoreB = ($b->kode_ruang == ($old[1] ?? null)) ? -50 : 0;
                $scoreA += $a->kapasitas >= $jumlahMahasiswa ? 0 : 1000;
                $scoreB += $b->kapasitas >= $jumlahMahasiswa ? 0 : 1000;
                $scoreA += max(0, $a->kapasitas - $jumlahMahasiswa);
                $scoreB += max(0, $b->kapasitas - $jumlahMahasiswa);
                return $scoreA <=> $scoreB;
            });

            if ($aggressive) {
                $ruangList = array_slice($ruangList, 0, 24);
            } else {
                $ruangList = array_slice($ruangList, 0, 15);
            }

            if ($jenisMatkul === 'praktikum') {
                $urutanHari = [6, 7];
            } else {
                $urutanHari = $kodeHariAktif;
            }
            shuffle($urutanHari);
            usort($urutanHari, function ($a, $b) use ($hariLoad) {
                if (($hariLoad[$a] ?? 0) == ($hariLoad[$b] ?? 0)) {
                    return mt_rand(-1, 1);
                }

                return ($hariLoad[$a] ?? 0) <=> ($hariLoad[$b] ?? 0);
            });

            $waktuCandidates = [];
            $seenWaktu = [];

            // kode_hari stored at index [2] - prioritas is now hari-based
            $kodeHariPrioritas = $prioritasMap[$idKelas] ?? null;
            // (waktu table removed - priority injected via urutanHari ordering below)

            foreach ($urutanHari as $kodeHari) {
                $listWaktuHari = $this->getWaktuCandidatesByHariAndSks($kodeHari, $jumlahSks);
                if ($aggressive && count($listWaktuHari) > 32) {
                    $listWaktuHari = array_slice($listWaktuHari, 0, 32);
                } elseif (!$aggressive && count($listWaktuHari) > 16) {
                    $listWaktuHari = array_slice($listWaktuHari, 0, 16);
                }

                foreach ($listWaktuHari as $waktu) {
                    $wKey = $waktu->kode_hari . '_' . $waktu->kode_jam;
                    if (!isset($seenWaktu[$wKey])) {
                        $waktuCandidates[] = $waktu;
                        $seenWaktu[$wKey] = true;
                    }
                }
            }

            $bestChromosome = $old;
            $bestCandidate = null;
            $bestScore = PHP_INT_MAX;

            foreach ($waktuCandidates as $waktu) {
                $jam = isset($waktu->jam) ? $waktu->jam : null;
                if (!$jam && isset($waktu->kode_jam)) {
                    $jamRow = $this->getCachedJam($waktu->kode_jam);
                    $jam = $jamRow ? $jamRow->jam : null;
                }

                if (!$jam) {
                    continue;
                }

                $jamMulai = substr($jam, 0, 5);
                $jamSelesai = $this->hitungJamSelesai($jamMulai, $jumlahSks);
                $timeInvalid = $this->isJamSelesaiValid($jamMulai, $jumlahSks) ? 0 : 1;

                foreach ($ruangList as $ruang) {
                    // Cek kesesuaian tipe ruang
                    $tipeRuang = $ruang->tipe_ruang ?? 'reguler';
                    $roomMismatch = 0;
                    if ($jenisMatkul === 'praktikum' && $tipeRuang !== 'laboratorium') {
                        $roomMismatch = 1;
                    } elseif ($jenisMatkul === 'teori' && $tipeRuang === 'laboratorium') {
                        $roomMismatch = 1;
                    }

                    $isFisikaDasar = $this->isMatkulFisikaDasar($namaMatkul);
                    $isFisikaRuang = $this->isRuangFisika($ruang->nama_ruang);

                    if ($isFisikaDasar && !$isFisikaRuang) {
                        $roomMismatch = 1;
                    } elseif (!$isFisikaDasar && $isFisikaRuang) {
                        $roomMismatch = 1;
                    }

                    $candidate = [
                        'kode_hari' => $waktu->kode_hari,
                        'kode_jam' => $waktu->kode_jam,
                        'kode_ruang' => $ruang->kode_ruang,
                        'kode_dosen_list' => $kodeDosenList,
                        'kode_rombel' => $kodeRombel,
                        'jam_mulai' => $jamMulai,
                        'jam_selesai' => $jamSelesai,
                        'blocked' => $this->isCandidateBlockedByDosen($kodeDosenList, $waktu->kode_hari, $jamMulai, $jamSelesai) ? 1 : 0,
                        'capacity_invalid' => $jumlahMahasiswa > $ruang->kapasitas ? 1 : 0,
                        'room_type_mismatch' => $roomMismatch,
                        'time_invalid' => $timeInvalid,
                    ];

                    $baseScore = $this->hitungSkorCandidateTerhadapDraftIndex($draftIndex, $candidate);
                    $score = $baseScore;
                    $score += ($hariLoad[$waktu->kode_hari] ?? 0) * 20;

                    // Penalty jika 3 SKS tidak di pagi hari
                    if ($jumlahSks == 3 && !$this->isSlotPagi($jamMulai)) {
                        $score += 150;
                    }

                    if ($kodeHariPrioritas && $waktu->kode_hari != $kodeHariPrioritas) {
                        $score += 5;
                    }

                    // Noise kecil untuk mencegah repair selalu memilih slot/ruang yang sama saat banyak kandidat nilainya mirip.
                    // Nilainya jauh lebih kecil daripada penalty bentrok, jadi kualitas tetap diprioritaskan.
                    $score += mt_rand(0, $aggressive ? 500 : 1500);

                    if ($score < $bestScore) {
                        $bestScore = $score;
                        $bestCandidate = $candidate;
                        $bestChromosome = [$idKelas, $ruang->kode_ruang, $waktu->kode_hari];

                        // Jangan langsung berhenti di kandidat nol pertama. Biarkan kandidat lain ikut bersaing
                        // supaya hasil akhir tidak selalu identik pada setiap klik generate.
                        if ($baseScore == 0 && !$aggressive && mt_rand(1, 100) <= 8) {
                            break 2;
                        }
                    }
                }
            }

            $hasil[$idx] = $bestChromosome;

            if ($bestCandidate !== null) {
                $draft[] = $bestCandidate;
                $this->tambahCandidateKeDraftIndex($draftIndex, $bestCandidate);
                if (isset($hariLoad[$bestCandidate['kode_hari']])) {
                    $hariLoad[$bestCandidate['kode_hari']]++;
                }
            }
        }

        ksort($hasil);
        return array_values($hasil);
    }

    // LEGACY: fungsi ini tidak dipanggil lagi pada versi GA murni.
    // Dibiarkan agar tidak mengganggu bagian lain jika pernah direferensikan manual.
    private function seimbangkanJadwalFinal(array $jadwalIndividu)
    {
        $kodeHariAktif = $this->getKodeHariAktifKuliah();

        if (count($kodeHariAktif) == 0 || count($jadwalIndividu) == 0) {
            return $jadwalIndividu;
        }

        $jumlahPerHari = [];
        foreach ($kodeHariAktif as $kodeHari) {
            $jumlahPerHari[$kodeHari] = 0;
        }

        $draft = [];
        $hasil = [];

        usort($jadwalIndividu, function ($a, $b) {
            $sksA = (int) ($a['jumlah_sks'] ?? 1);
            $sksB = (int) ($b['jumlah_sks'] ?? 1);

            if ($sksA == $sksB) {
                return ($a['id_kelas'] ?? 0) <=> ($b['id_kelas'] ?? 0);
            }

            return $sksB <=> $sksA;
        });

        foreach ($jadwalIndividu as $row) {
            $kelasMatkul = $this->cacheLoaded
                ? $this->getCachedKelasMatkul($row['id_kelas'] ?? null)
                : DB::table('kelas_matkul')->where('id_kelas', $row['id_kelas'] ?? null)->first();

            $jumlahSks = (int) ($row['jumlah_sks'] ?? $this->getJumlahSksByKelasMatkul($kelasMatkul));
            $jumlahMahasiswa = (int) ($row['jumlah_mahasiswa'] ?? ($kelasMatkul->jumlah_mahasiswa ?? 0));
            $kodeProdi = $kelasMatkul->kode_prodi ?? null;
            $kodeDosenList = $row['kode_dosen']['list'] ?? [];
            $kodeRombel = $row['kode_rombel'] ?? '-';

            $urutanHari = $kodeHariAktif;
            usort($urutanHari, function ($a, $b) use ($jumlahPerHari) {
                if ($jumlahPerHari[$a] == $jumlahPerHari[$b]) {
                    return $a <=> $b;
                }

                return $jumlahPerHari[$a] <=> $jumlahPerHari[$b];
            });

            $jenisMatkul = $this->getJenisMatkulByKelasMatkul($kelasMatkul);
            $namaMatkul = $this->getNamaMatkulByKelasMatkul($kelasMatkul);
            $ruangList = $this->getRuangAlternatifValid($kodeProdi, $jumlahMahasiswa, $jenisMatkul, $namaMatkul);
            $terpasang = false;

            foreach ($urutanHari as $kodeHari) {
                $waktuCandidates = $this->getWaktuCandidatesByHariAndSks($kodeHari, $jumlahSks);

                foreach ($waktuCandidates as $waktu) {
                    $jamMulai = substr($waktu->jam, 0, 5);
                    $jamSelesai = $this->hitungJamSelesai($jamMulai, $jumlahSks);

                    foreach ($ruangList as $ruang) {
                        $candidate = [
                            'kode_hari' => $kodeHari,
                            'kode_jam' => $waktu->kode_jam,
                            'kode_ruang' => $ruang->kode_ruang,
                            'kode_dosen_list' => $kodeDosenList,
                            'kode_rombel' => $kodeRombel,
                            'jam_mulai' => $jamMulai,
                            'jam_selesai' => $jamSelesai,
                        ];

                        if ($this->isBentrokDenganDraft($draft, $candidate)) {
                            continue;
                        }

                        $row['kode_hari'] = $kodeHari;
                        $row['kode_jam'] = $waktu->kode_jam;
                        $row['jam_selesai'] = $jamSelesai;
                        $row['time_invalid'] = 0;
                        $row['nama_ruang']['kode'] = $ruang->nama_ruang;
                        $row['nama_ruang']['kode_ruang'] = $ruang->kode_ruang;
                        $row['nama_ruang']['kapasitas'] = $ruang->kapasitas;
                        $row['nama_ruang']['capacity_invalid'] = $jumlahMahasiswa > $ruang->kapasitas ? 1 : 0;
                        $row['nama_ruang']['clash'] = 0;
                        $row['kelas_clash'] = 0;
                        $row['kode_dosen']['clash'] = 0;

                        $draft[] = $candidate;
                        $jumlahPerHari[$kodeHari]++;
                        $hasil[] = $row;
                        $terpasang = true;
                        break 3;
                    }
                }
            }

            if (!$terpasang) {
                $kodeHariFallback = $row['kode_hari'] ?? $kodeHariAktif[0];

                if (!array_key_exists($kodeHariFallback, $jumlahPerHari)) {
                    $kodeHariFallback = $kodeHariAktif[0];
                }

                $jumlahPerHari[$kodeHariFallback]++;
                $hasil[] = $row;
            }
        }

        usort($hasil, function ($a, $b) {
            if (($a['kode_hari'] ?? 0) == ($b['kode_hari'] ?? 0)) {
                if (($a['kode_jam'] ?? 0) == ($b['kode_jam'] ?? 0)) {
                    return strcmp((string) ($a['nama_kelas'] ?? ''), (string) ($b['nama_kelas'] ?? ''));
                }

                return ($a['kode_jam'] ?? 0) <=> ($b['kode_jam'] ?? 0);
            }

            return ($a['kode_hari'] ?? 0) <=> ($b['kode_hari'] ?? 0);
        });

        return $hasil;
    }



    // LEGACY: fungsi deterministik ini tidak dipanggil lagi pada versi GA murni.
    // Hasil generate sekarang berasal dari proses GA, bukan override greedy/deterministik.
    private function buatJadwalTerdistribusiDeterministik($kelasMatkulTable)
    {
        $kodeHariAktif = $this->getKodeHariAktifKuliah();

        if (count($kodeHariAktif) == 0 || count($kelasMatkulTable) == 0) {
            return [];
        }

        $hariLoad = [];
        foreach ($kodeHariAktif as $kodeHari) {
            $hariLoad[$kodeHari] = 0;
        }

        $draft = [];
        $hasil = [];

        $kelasList = collect($kelasMatkulTable)->sortBy(function ($kelas) {
            return [
                $kelas->kode_matkul ?? '',
                $kelas->nama_kelas ?? '',
                $kelas->id_kelas ?? 0,
            ];
        })->values();

        foreach ($kelasList as $kelasMatkul) {
            $matkul = $this->cacheLoaded
                ? $this->getCachedMatkul($kelasMatkul->kode_matkul, $kelasMatkul->tahun_ajaran)
                : DB::table('matkul')->where('kode_matkul', $kelasMatkul->kode_matkul)->where('tahun_ajaran', $kelasMatkul->tahun_ajaran)->first();

            if (!$matkul) {
                $matkul = $this->cacheLoaded
                    ? $this->getCachedMatkul($kelasMatkul->kode_matkul)
                    : DB::table('matkul')->where('kode_matkul', $kelasMatkul->kode_matkul)->first();
            }

            if (!$matkul) {
                continue;
            }

            $kodeDosenList = $this->getKodeDosenListByIdKelas($kelasMatkul->id_kelas);
            $kodeDosenList = array_values(array_unique(array_filter($kodeDosenList)));

            if (count($kodeDosenList) < 1) {
                continue;
            }

            $kodeDosenList = $this->pilihDuaDosenAcak($kodeDosenList, 2);
            $jumlahSks = (int) ($matkul->sks ?: 1);
            $jumlahMahasiswa = (int) ($kelasMatkul->jumlah_mahasiswa ?: 0);
            $kodeRombel = $kelasMatkul->kode_rombel ?: ($kelasMatkul->kode_matkul . $kelasMatkul->nama_kelas);
            $jenisMatkul = $matkul ? $matkul->jenis_matkul : 'teori';
            $namaMatkul = $matkul ? $matkul->nama_matkul : '';
            $ruangList = $this->getRuangAlternatifValid($kelasMatkul->kode_prodi ?? ($matkul->kode_prodi ?? null), $jumlahMahasiswa, $jenisMatkul, $namaMatkul);

            $urutanHari = $kodeHariAktif;
            usort($urutanHari, function ($a, $b) use ($hariLoad) {
                if ($hariLoad[$a] == $hariLoad[$b]) {
                    return $a <=> $b;
                }

                return $hariLoad[$a] <=> $hariLoad[$b];
            });

            $terpasang = false;

            foreach ($urutanHari as $kodeHari) {
                $waktuCandidates = $this->getWaktuCandidatesByHariAndSks($kodeHari, $jumlahSks);

                foreach ($waktuCandidates as $waktu) {
                    $jamMulai = substr($waktu->jam, 0, 5);
                    $jamSelesai = $this->hitungJamSelesai($jamMulai, $jumlahSks);

                    foreach ($ruangList as $ruang) {
                        $candidate = [
                            'kode_hari' => $kodeHari,
                            'kode_jam' => $waktu->kode_jam,
                            'kode_ruang' => $ruang->kode_ruang,
                            'kode_dosen_list' => $kodeDosenList,
                            'kode_rombel' => $kodeRombel,
                            'jam_mulai' => $jamMulai,
                            'jam_selesai' => $jamSelesai,
                        ];

                        if ($this->isBentrokDenganDraft($draft, $candidate)) {
                            continue;
                        }

                        $hasil[] = [
                            'id_kelas' => $kelasMatkul->id_kelas,
                            'kode_matkul' => $kelasMatkul->kode_matkul,
                            'kode_dosen' => [
                                'kode' => $kodeDosenList[0],
                                'list' => $kodeDosenList,
                                'clash' => 0,
                                'blocked' => 0,
                            ],
                            'kode_kelas' => $kelasMatkul->id_kelas,
                            'nama_kelas' => $kelasMatkul->nama_kelas,
                            'kode_rombel' => $kodeRombel,
                            'kelas_clash' => 0,
                            'jumlah_mahasiswa' => $jumlahMahasiswa,
                            'jumlah_sks' => $jumlahSks,
                            'jam_selesai' => $jamSelesai,
                            'time_invalid' => 0,
                            'nama_ruang' => [
                                'kode' => $ruang->nama_ruang,
                                'kode_ruang' => $ruang->kode_ruang,
                                'kapasitas' => $ruang->kapasitas,
                                'capacity_invalid' => $jumlahMahasiswa > $ruang->kapasitas ? 1 : 0,
                                'clash' => 0,
                            ],
                            'kode_hari' => $kodeHari,
                            'kode_jam' => $waktu->kode_jam,
                        ];

                        $draft[] = $candidate;
                        $hariLoad[$kodeHari]++;
                        $terpasang = true;
                        break 3;
                    }
                }
            }

            if (!$terpasang) {
                // Fallback terakhir: tetap pasang di hari paling ringan dengan slot valid pertama.
                // Ini menjaga semua kelas tidak hilang, tetapi tetap menghindari menumpuk di Jumat saja.
                $kodeHariFallback = array_key_first($hariLoad);
                foreach ($hariLoad as $kodeHari => $total) {
                    if ($total < $hariLoad[$kodeHariFallback]) {
                        $kodeHariFallback = $kodeHari;
                    }
                }

                $waktuCandidates = $this->getWaktuCandidatesByHariAndSks($kodeHariFallback, $jumlahSks);
                $ruang = count($ruangList) > 0 ? $ruangList[0] : DB::table('ruang')->first();

                if (count($waktuCandidates) == 0 || !$ruang) {
                    continue;
                }

                $waktu = $waktuCandidates[0];
                $jamMulai = substr($waktu->jam, 0, 5);
                $jamSelesai = $this->hitungJamSelesai($jamMulai, $jumlahSks);

                $hasil[] = [
                    'id_kelas' => $kelasMatkul->id_kelas,
                    'kode_matkul' => $kelasMatkul->kode_matkul,
                    'kode_dosen' => [
                        'kode' => $kodeDosenList[0],
                        'list' => $kodeDosenList,
                        'clash' => 0,
                        'blocked' => 0,
                    ],
                    'kode_kelas' => $kelasMatkul->id_kelas,
                    'nama_kelas' => $kelasMatkul->nama_kelas,
                    'kode_rombel' => $kodeRombel,
                    'kelas_clash' => 0,
                    'jumlah_mahasiswa' => $jumlahMahasiswa,
                    'jumlah_sks' => $jumlahSks,
                    'jam_selesai' => $jamSelesai,
                    'time_invalid' => 0,
                    'nama_ruang' => [
                        'kode' => $ruang->nama_ruang,
                        'kode_ruang' => $ruang->kode_ruang,
                        'kapasitas' => $ruang->kapasitas,
                        'capacity_invalid' => $jumlahMahasiswa > $ruang->kapasitas ? 1 : 0,
                        'clash' => 0,
                    ],
                    'kode_hari' => $kodeHariFallback,
                    'kode_jam' => $waktu->kode_jam,
                ];

                $hariLoad[$kodeHariFallback]++;
            }
        }

        usort($hasil, function ($a, $b) {
            if (($a['kode_hari'] ?? 0) == ($b['kode_hari'] ?? 0)) {
                if (($a['kode_jam'] ?? 0) == ($b['kode_jam'] ?? 0)) {
                    return strcmp((string) ($a['nama_kelas'] ?? ''), (string) ($b['nama_kelas'] ?? ''));
                }

                return ($a['kode_jam'] ?? 0) <=> ($b['kode_jam'] ?? 0);
            }

            return ($a['kode_hari'] ?? 0) <=> ($b['kode_hari'] ?? 0);
        });

        return $hasil;
    }


    private function pilihDosenOtomatisByKodeProdi($kodeProdi, array $excludeKodeDosen = [], $limit = 2)
    {
        $candidate = $this->getDosenCandidateByKodeProdi($kodeProdi);
        $candidate = array_values(array_diff($candidate, $excludeKodeDosen));

        if (count($candidate) < $limit) {
            $global = array_values(array_diff($this->getDosenCandidateGlobal(), array_merge($excludeKodeDosen, $candidate)));
            $candidate = array_merge($candidate, $global);
        }

        return $this->pilihDuaDosenAcak($candidate, $limit);
    }


    private function pilihDosenOtomatisGlobal(array $excludeKodeDosen = [], $limit = 2)
    {
        $candidate = array_values(array_diff($this->getDosenCandidateGlobal(), $excludeKodeDosen));

        return $this->pilihDuaDosenAcak($candidate, $limit);
    }


    private function sinkronkanKelasDenganDuaDosen($kelasMatkul, $matkul)
    {
        // Konsep baru: Manage Kelas tidak menyimpan relasi dosen.
        // Fungsi ini hanya validasi bahwa sistem punya minimal 2 dosen yang bisa dipilih saat generate.
        if (!$kelasMatkul || !$matkul) {
            return false;
        }

        $kodeDosenList = $this->getKodeDosenListByIdKelas($kelasMatkul->id_kelas);

        return count($kodeDosenList) >= 2;
    }


    private function prepareKelasMatkulOtomatis($tahunAjaran, $kodeSemester)
    {
        $matkulList = DB::table('matkul')
            ->where('tahun_ajaran', $tahunAjaran)
            ->where('kode_semester', $kodeSemester)
            ->orderBy('kode_matkul')
            ->get();

        if (count($matkulList) == 0) {
            return [
                'success' => false,
                'message' => 'Data mata kuliah untuk semester dan tahun ajaran tersebut belum ada.',
            ];
        }

        // Sinkronisasi data kelas dari tabel `kelas` (sumber data Kelola Kelas) ke tabel `kelas_matkul`
        foreach ($matkulList as $matkul) {
            // Ambil semua kelas yang didaftarkan user di Kelola Kelas (tabel kelas) untuk matkul ini
            $kelasDbList = DB::table('kelas')
                ->where('tahun_ajaran', $tahunAjaran)
                ->where('nama_matkul', strtolower($matkul->nama_matkul))
                ->get();

            if ($kelasDbList->count() > 0) {
                $validNamaKelas = [];
                foreach ($kelasDbList as $kelasDb) {
                    $namaKelasUpper = strtoupper(trim($kelasDb->kelas));
                    $validNamaKelas[] = $namaKelasUpper;

                    $existing = DB::table('kelas_matkul')
                        ->where('kode_matkul', $matkul->kode_matkul)
                        ->where('nama_kelas', $namaKelasUpper)
                        ->where('tahun_ajaran', $tahunAjaran)
                        ->first();

                    if ($existing) {
                        DB::table('kelas_matkul')
                            ->where('id_kelas', $existing->id_kelas)
                            ->update([
                                'kode_rombel'      => $kelasDb->kode_kelas,
                                'jumlah_mahasiswa' => $kelasDb->kapasitas_kelas,
                                'kode_semester'    => $matkul->kode_semester,
                                'kode_prodi'       => $matkul->kode_prodi,
                                'updated_at'       => now(),
                            ]);
                    } else {
                        DB::table('kelas_matkul')->insert([
                            'kode_matkul'      => $matkul->kode_matkul,
                            'nama_kelas'       => $namaKelasUpper,
                            'kode_rombel'      => $kelasDb->kode_kelas,
                            'jumlah_mahasiswa' => $kelasDb->kapasitas_kelas,
                            'kode_semester'    => $matkul->kode_semester,
                            'tahun_ajaran'     => $tahunAjaran,
                            'kode_prodi'       => $matkul->kode_prodi,
                            'created_at'       => now(),
                            'updated_at'       => now(),
                        ]);
                    }
                }

                // Hapus data kelas_matkul yang sudah tidak ada di Kelola Kelas
                DB::table('kelas_matkul')
                    ->where('kode_matkul', $matkul->kode_matkul)
                    ->where('tahun_ajaran', $tahunAjaran)
                    ->whereNotIn('nama_kelas', $validNamaKelas)
                    ->delete();
            } else {
                // Hapus data kelas_matkul yang sudah tidak valid
                DB::table('kelas_matkul')
                    ->where('kode_matkul', $matkul->kode_matkul)
                    ->where('tahun_ajaran', $tahunAjaran)
                    ->delete();

                // Jika user sama sekali tidak mendaftarkan kelas untuk matkul ini, buatkan kelas default A
                // di tabel `kelas` dan `kelas_matkul` agar tetap sinkron.
                $kelasDefault = 'A';
                $kodeKelas = $matkul->kode_matkul . $kelasDefault;
                $jumlahMahasiswaDefault = 40;

                DB::table('kelas')->insert([
                    'kode_kelas'      => $kodeKelas,
                    'nama_matkul'     => strtolower($matkul->nama_matkul),
                    'nama_dosen'      => 'Ditentukan saat generate jadwal',
                    'kelas'           => $kelasDefault,
                    'kapasitas_kelas' => $jumlahMahasiswaDefault,
                    'tahun_ajaran'    => $tahunAjaran,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);

                DB::table('kelas_matkul')->insert([
                    'kode_matkul'      => $matkul->kode_matkul,
                    'nama_kelas'       => $kelasDefault,
                    'kode_rombel'      => $kodeKelas,
                    'jumlah_mahasiswa' => $jumlahMahasiswaDefault,
                    'kode_semester'    => $matkul->kode_semester,
                    'tahun_ajaran'     => $tahunAjaran,
                    'kode_prodi'       => $matkul->kode_prodi,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            }
        }

        $kelasMatkulList = DB::table('kelas_matkul')
            ->where('kode_semester', $kodeSemester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->orderBy('kode_matkul')
            ->orderBy('nama_kelas')
            ->get();

        if (count($kelasMatkulList) == 0) {
            return [
                'success' => false,
                'message' => 'Data kelas untuk semester dan tahun ajaran tersebut belum ada.',
            ];
        }

        if (DB::table('dosen')->count() < 2) {
            return [
                'success' => false,
                'message' => 'Data dosen kurang dari 2. Tambahkan minimal 2 dosen agar sistem bisa memilih dosen saat generate.',
            ];
        }

        return [
            'success' => true,
            'message' => 'Data kelas berhasil disiapkan dan disinkronkan dari Kelola Kelas.',
        ];
    }

    public function generatejadwalform(Request $request)
    {
        $user_login = $request->session()->get('user_login');

        $countRequest = DB::table('request_kuliah')->count()
            + DB::table('request_ruang')->count()
            + DB::table('request_waktu')->count();

        $semester = DB::table('semester')->get();
        $allTahunAjaran = DB::table('tahun_ajaran')->get();
        $allDosen = DB::table('dosen')->get();
        $allHari = DB::table('hari')->get();

        $countKuliahTabel = [];

        foreach ($allTahunAjaran as $tahun) {
            $countKuliahTabel[] = [
                'tahun_ajaran' => $tahun->tahun_ajaran,
                'semester_ganjil_count' => DB::table('kelas_matkul')
                    ->where('kode_semester', 1)
                    ->where('tahun_ajaran', $tahun->tahun_ajaran)
                    ->count(),
                'semester_genap_count' => DB::table('kelas_matkul')
                    ->where('kode_semester', 2)
                    ->where('tahun_ajaran', $tahun->tahun_ajaran)
                    ->count(),
            ];
        }

        if (DB::table('matkul')->count() == 0) {
            return redirect('/managekuliah/managematkul')
                ->with('status', 'Harap mengisi data mata kuliah terlebih dahulu!');
        }

        if (DB::table('dosen')->count() == 0) {
            return redirect('/managekuliah/managedosen')
                ->with('status', 'Harap mengisi data dosen terlebih dahulu!');
        }

        if (DB::table('ruang')->count() == 0) {
            return redirect('/manageruang')
                ->with('status', 'Harap mengisi data ruang terlebih dahulu!');
        }



        $algoritma_proses = [];
        $execution_time = [];

        return view('penjadwalankuliah.generatejadwal', compact(
            'user_login',
            'semester',
            'algoritma_proses',
            'countRequest',
            'execution_time',
            'allDosen',
            'allHari',
            'countKuliahTabel',
            'allTahunAjaran'
        ));
    }

    public function generate_action(Request $request)
    {
        if (!$request->ajax()) {
            return;
        }

        if ($request->has('dosen')) {
            $namaDosen = $request->get('dosen');

            $dosen = DB::table('dosen')
                ->where('nama', $namaDosen)
                ->first();

            if (!$dosen) {
                echo json_encode(['allKelas' => []]);
                return;
            }

            $kelasBySemesterAndYear = DB::table('kelas_matkul')
                ->where('kode_semester', $request->get('semester'))
                ->where('tahun_ajaran', $request->get('tahun_ajaran'))
                ->orderBy('kode_matkul')
                ->orderBy('nama_kelas')
                ->get();

            echo json_encode([
                'allKelas' => $kelasBySemesterAndYear
            ]);

            return;
        }

        if ($request->has('hari')) {
            // waktu/jam tables removed - return generated time slots from 07:00 to 17:15
            $allJamByKodeJam = [];
            $slotMulai = 7 * 60; // 07:00 in minutes
            $batasMenit = 17 * 60 + 15; // 17:15
            $slotIdx = 1;

            while ($slotMulai < $batasMenit) {
                $jam = str_pad(floor($slotMulai / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad($slotMulai % 60, 2, '0', STR_PAD_LEFT);
                // Skip lunch
                if ($slotMulai >= 12 * 60 && $slotMulai < 13 * 60) {
                    $slotMulai += 50;
                    continue;
                }
                $allJamByKodeJam[$slotIdx] = (object)['kode_jam' => $slotIdx, 'jam' => $jam];
                $slotMulai += 50;
                $slotIdx++;
            }

            echo json_encode([
                'allJam' => $allJamByKodeJam
            ]);

            return;
        }

        if ($request->has('i')) {
            echo json_encode([
                'dosen' => DB::table('dosen')->get(),
                'hari' => DB::table('hari')->get()
            ]);

            return;
        }
    }

    public function generatejadwal(Request $request)
    {
        $this->jamMulaiSetting = $request->jam_mulai ?? '07:00';
        $this->jamTerakhirSetting = $request->jam_terakhir_mulai ?? '17:00';
        $this->durasiSksSetting = (int) ($request->durasi_sks ?? 50);
        $this->jedaSetting = (int) ($request->jeda ?? 10);
        $this->istirahatMulaiSetting = $request->istirahat_mulai ?? '12:00';
        $this->istirahatSelesaiSetting = $request->istirahat_selesai ?? '13:00';

        $jamMulaiSetting = $this->jamMulaiSetting;
        $durasiSksSetting = $this->durasiSksSetting;
        $jedaSetting = $this->jedaSetting;
        $istirahatMulaiSetting = $this->istirahatMulaiSetting;
        $istirahatSelesaiSetting = $this->istirahatSelesaiSetting;

        // Versi GA murni min-bentrok.
        // Tetap memakai populasi, fitness, seleksi, crossover, mutasi, elitism, dan repair operator.
        ini_set('memory_limit', '1536M');
        // Hindari fatal error PHP "Maximum execution time exceeded".
        // Batas waktu generate tetap dikontrol manual oleh $batasWaktuGenerateDetik.
        ini_set('max_execution_time', 0);
        set_time_limit(0);

        // Seed random dibuat baru setiap klik generate agar hasil GA tidak selalu jatuh ke pola yang sama.
        try {
            $seed = ((int) (microtime(true) * 1000000))
                + random_int(1, 999999999)
                + hexdec(substr(hash('crc32b', uniqid('', true) . random_bytes(8)), 0, 7));
        } catch (\Exception $e) {
            $seed = ((int) (microtime(true) * 1000000))
                + mt_rand(1, 999999999)
                + hexdec(substr(hash('crc32b', uniqid('', true)), 0, 7));
        }
        mt_srand($seed);
        srand($seed);

        $user_login = $request->session()->get('user_login');

        $countRequest = DB::table('request_kuliah')->count()
            + DB::table('request_ruang')->count()
            + DB::table('request_waktu')->count();

        $semester = DB::table('semester')->get();
        $allDosen = DB::table('dosen')->get();
        $allHari = DB::table('hari')->get();
        $allTahunAjaran = DB::table('tahun_ajaran')->get();

        $jumlahIndividu = (int) $request->individu;
        $maxGenerasi = (int) $request->generasi;
        $tahunAjaran = $request->tahun_ajaran;
        $kodeSemester = $request->radioSemester;
        $showAlgorithm = $request->algoritma;
        $crossoverRate = (int) $request->crossover_rate;

        $countKuliahTabel = [];

        foreach ($allTahunAjaran as $tahun) {
            $countKuliahTabel[] = [
                'tahun_ajaran' => $tahun->tahun_ajaran,
                'semester_ganjil_count' => DB::table('kelas_matkul')
                    ->where('kode_semester', 1)
                    ->where('tahun_ajaran', $tahun->tahun_ajaran)
                    ->count(),
                'semester_genap_count' => DB::table('kelas_matkul')
                    ->where('kode_semester', 2)
                    ->where('tahun_ajaran', $tahun->tahun_ajaran)
                    ->count(),
            ];
        }

        if (!$kodeSemester) {
            Session::flash('errorSemester', "Harap Memilih Semester Terlebih Dahulu!");
            return Redirect::back();
        }

        if (!$tahunAjaran) {
            Session::flash('errorTahunAjaran', "Harap Memilih Tahun Ajaran Terlebih Dahulu!");
            return Redirect::back();
        }

        if ($maxGenerasi < 1) {
            Session::flash('errorJumlahGenerasi', "Generasi Minimal 1!");
            return Redirect::back();
        }

        if ($jumlahIndividu < 4) {
            Session::flash('errorJumlahIndividu', "Individu Minimal 4!");
            return Redirect::back();
        }

        if ($crossoverRate < 1 || $crossoverRate > 75) {
            Session::flash('errorCrossoverRate', "Crossover Rate harus di antara 1 sampai 75!");
            return Redirect::back();
        }

        $prepare = $this->prepareKelasMatkulOtomatis($tahunAjaran, $kodeSemester);

        if (!$prepare['success']) {
            return redirect('/managekuliah/managematkul')
                ->with('status', $prepare['message']);
        }

        $kelasMatkulTable = DB::table('kelas_matkul')
            ->where('kode_semester', $kodeSemester)
            ->where('tahun_ajaran', $tahunAjaran)
            ->inRandomOrder()
            ->get();

        if (count($kelasMatkulTable) == 0) {
            $namaSemester = DB::table('semester')
                ->where('kode_semester', $kodeSemester)
                ->value('nama_semester');

            return redirect('/managekuliah/managematkul')
                ->with('status', 'Data kelas otomatis belum terbentuk untuk Semester ' . $namaSemester . ' Tahun Ajaran ' . $tahunAjaran);
        }

        $ruangTable = DB::table('ruang')->get();
        $waktuTable = []; // Not used anymore
        
        $kodeHariAktifKuliah = $this->getKodeHariAktifKuliah();
        if (count($kodeHariAktifKuliah) == 0) {
            return redirect('/managehari')->with('status', 'Data hari aktif Senin sampai Jumat belum lengkap. Pastikan tabel hari memiliki Senin, Selasa, Rabu, Kamis, dan Jumat.');
        }

        // === PRELOAD: Muat semua data referensi ke memori ===
        $this->preloadReferenceData();

        $prioritas_kelas = [];
        $kelas = $request->kelas;
        $hari = [];

        foreach ((array) $request->hari as $h) {
            if ($h != null) {
                $hari[] = $h;
            }
        }

        // waktu/jam tables removed. Priority is now hari-based.
        $kode_waktu = [];
        foreach ((array) $hari as $key => $value) {
            $kode_waktu[$key] = $value; // store kode_hari directly
        }

        foreach ((array) $kelas as $key => $value) {
            if (isset($kode_waktu[$key])) {
                $prioritas_kelas[] = [
                    'id_kelas'   => $value,
                    'kode_hari'  => $kode_waktu[$key],
                    'kode_waktu' => $kode_waktu[$key], // kept for backward compat
                ];
            }
        }

        $randomKodeRuang = function ($kode_prodi, $jumlah_mahasiswa, $jenis_matkul = null, $nama_matkul = null) {
            $ruangList = $this->getRuangAlternatifValid($kode_prodi, $jumlah_mahasiswa, $jenis_matkul, $nama_matkul);

            $allKodeRuang = [];

            foreach ($ruangList as $ruang) {
                $allKodeRuang[] = $ruang->kode_ruang;
            }

            if (count($allKodeRuang) == 0) {
                $allKodeRuang = [''];
            }

            return $allKodeRuang[mt_rand(0, count($allKodeRuang) - 1)];
        };

        $random_1 = function ($individu) {
            $random = [];

            for ($i = 0; $i < count($individu); $i++) {
                $random[$i] = rand(0, 1000) / 1000;
            }

            return $random;
        };

        $random_2 = function ($individu) {
            $length = count($individu) - 1;

            return $length <= 1 ? 1 : rand(1, $length);
        };

        // Index blocking dosen agar tidak query/loop semua data blocking pada setiap kromosom.
        $blockingRows = $this->cacheLoaded ? $this->cacheBlocking : DB::table('blocking_jadwal_dosen')->get();
        $blockingByDosenHari = [];

        foreach ($blockingRows as $b) {
            $hariBlocking = strtolower(trim((string) $b->hari));
            $hariBlocking = str_replace(["'", '`', chr(0xE2).chr(0x80).chr(0x99)], '', $hariBlocking);
            $blockingByDosenHari[$b->kode_dosen][$hariBlocking][] = [
                'mulai' => substr($b->jam_mulai, 0, 5),
                'selesai' => substr($b->jam_selesai, 0, 5),
            ];
        }

        $individuWithDetail = function ($individu) use ($blockingByDosenHari, $jamMulaiSetting, $durasiSksSetting, $jedaSetting, $istirahatMulaiSetting, $istirahatSelesaiSetting) {
            $individuWithDetail = [];

            for ($i = 0; $i < count($individu); $i++) {
                $individuWithDetail[$i] = [];
                $roomDayBuckets = [];

                for ($j = 0; $j < count($individu[$i]); $j++) {
                    $idKelas = $individu[$i][$j][0];
                    $kodeRuang = $individu[$i][$j][1];
                    $kodeHari = $individu[$i][$j][2];

                    $kelasMatkul = $this->getCachedKelasMatkul($idKelas);
                    $kodeDosenList = $this->getKodeDosenListByIdKelas($idKelas);
                    $ruang = $this->getCachedRuang($kodeRuang);
                    $hari = $this->getCachedHari($kodeHari);

                    if (!$kelasMatkul || count($kodeDosenList) < 1 || !$ruang || !$hari) {
                        $individuWithDetail[$i][$j] = [
                            'id_kelas' => $idKelas,
                            'kode_matkul' => $kelasMatkul->kode_matkul ?? '',
                            'kode_dosen' => [
                                'kode' => $kodeDosenList[0] ?? '',
                                'list' => $kodeDosenList,
                                'clash' => 1,
                                'blocked' => 0
                            ],
                            'kode_kelas' => $idKelas,
                            'nama_kelas' => $kelasMatkul->nama_kelas ?? '',
                            'kode_rombel' => $kelasMatkul->kode_rombel ?? '-',
                            'kelas_clash' => 1,
                            'jumlah_mahasiswa' => $kelasMatkul->jumlah_mahasiswa ?? 0,
                            'jumlah_sks' => 1,
                            'jam_selesai' => '07:50',
                            'time_invalid' => 1,
                            'nama_ruang' => [
                                'kode' => $ruang->nama_ruang ?? '',
                                'kode_ruang' => $kodeRuang,
                                'kapasitas' => $ruang->kapasitas ?? 0,
                                'capacity_invalid' => 1,
                                'room_type_mismatch' => 1,
                                'clash' => 1
                            ],
                            'kode_hari' => $kodeHari,
                            'kode_jam' => '07:00',
                            'jam_mulai' => '07:00',
                            'jenis_matkul' => 'teori'
                        ];
                        continue;
                    }

                    $matkul = $this->getCachedMatkul($kelasMatkul->kode_matkul, $kelasMatkul->tahun_ajaran);
                    if (!$matkul) {
                        $matkul = $this->getCachedMatkul($kelasMatkul->kode_matkul);
                    }
                    $jumlahSks = $matkul ? $matkul->sks : 1;
                    $jenisMatkul = $this->getJenisMatkulByKelasMatkul($kelasMatkul);
                    $namaMatkul = $matkul->nama_matkul ?? $this->getNamaMatkulByKelasMatkul($kelasMatkul);

                    $key = $kodeRuang . '_' . $kodeHari;
                    if (!isset($roomDayBuckets[$key])) {
                        $roomDayBuckets[$key] = [];
                    }

                    $roomDayBuckets[$key][] = [
                        'j_index' => $j,
                        'id_kelas' => $idKelas,
                        'kelasMatkul' => $kelasMatkul,
                        'kodeDosenList' => $kodeDosenList,
                        'ruang' => $ruang,
                        'hari' => $hari,
                        'jumlahSks' => $jumlahSks,
                        'jenisMatkul' => $jenisMatkul,
                        'namaMatkul' => $namaMatkul,
                    ];
                }

                // Process each room-day bucket
                foreach ($roomDayBuckets as $key => $bucket) {
                    usort($bucket, function ($a, $b) {
                        return $a['id_kelas'] <=> $b['id_kelas'];
                    });

                    $currentJam = $jamMulaiSetting;

                    foreach ($bucket as $item) {
                        $j = $item['j_index'];
                        $kelasMatkul = $item['kelasMatkul'];
                        $ruang = $item['ruang'];
                        $hari = $item['hari'];
                        $jumlahSks = $item['jumlahSks'];
                        $jenisMatkul = $item['jenisMatkul'];

                        $mulaiMinutes = $this->jamToMinutes($currentJam);
                        $durasiTotal = $jumlahSks * $durasiSksSetting;
                        $selesaiMinutes = $mulaiMinutes + $durasiTotal;

                        $istirahatMulaiMinutes = $this->jamToMinutes($istirahatMulaiSetting);
                        $istirahatSelesaiMinutes = $this->jamToMinutes($istirahatSelesaiSetting);

                        if ($mulaiMinutes < $istirahatMulaiMinutes && $selesaiMinutes > $istirahatMulaiMinutes) {
                            $mulaiMinutes = $istirahatSelesaiMinutes;
                            $selesaiMinutes = $mulaiMinutes + $durasiTotal;
                        } elseif ($mulaiMinutes >= $istirahatMulaiMinutes && $mulaiMinutes < $istirahatSelesaiMinutes) {
                            $mulaiMinutes = $istirahatSelesaiMinutes;
                            $selesaiMinutes = $mulaiMinutes + $durasiTotal;
                        }

                        $isCapacityInvalid = $kelasMatkul->jumlah_mahasiswa > $ruang->kapasitas ? 1 : 0;
                        $batasMulaiMenit = $this->jamToMinutes($this->jamTerakhirSetting);
                        $isTimeInvalid = 0;

                        if ($mulaiMinutes > $batasMulaiMenit) {
                            $mulaiMinutes = $batasMulaiMenit + 1;
                            $selesaiMinutes = $mulaiMinutes + $durasiTotal;
                            $isTimeInvalid = 1;
                            $currentJam = $this->minutesToJam($mulaiMinutes);
                        } else {
                            $nextMulaiMinutes = $selesaiMinutes + $jedaSetting;
                            $currentJam = $this->minutesToJam($nextMulaiMinutes);
                        }

                        $jamMulaiFinal = $this->minutesToJam($mulaiMinutes);
                        $jamSelesaiFinal = $this->minutesToJam($selesaiMinutes);

                        $tipeRuang = $ruang->tipe_ruang ?? 'reguler';
                        $roomTypeMismatch = 0;
                        if ($jenisMatkul === 'praktikum' && $tipeRuang !== 'laboratorium') {
                            $roomTypeMismatch = 1;
                        } elseif ($jenisMatkul === 'teori' && $tipeRuang === 'laboratorium') {
                            $roomTypeMismatch = 1;
                        }

                        $isFisikaDasar = $this->isMatkulFisikaDasar($item['namaMatkul']);
                        $isFisikaRuang = $this->isRuangFisika($ruang->nama_ruang);
                        if ($isFisikaDasar && !$isFisikaRuang) {
                            $roomTypeMismatch = 1;
                        } elseif (!$isFisikaDasar && $isFisikaRuang) {
                            $roomTypeMismatch = 1;
                        }

                        $isBlocked = 0;
                        if (count($blockingByDosenHari) > 0) {
                            if ($this->isCandidateBlockedByDosen($item['kodeDosenList'], $hari->kode_hari, $jamMulaiFinal, $jamSelesaiFinal)) {
                                $isBlocked = 1;
                            }
                        }

                        $individuWithDetail[$i][$j] = [
                            'id_kelas' => $kelasMatkul->id_kelas,
                            'kode_matkul' => $kelasMatkul->kode_matkul,
                            'kode_dosen' => [
                                'kode' => $item['kodeDosenList'][0],
                                'list' => $item['kodeDosenList'],
                                'clash' => 0,
                                'blocked' => $isBlocked
                            ],
                            'kode_kelas' => $kelasMatkul->id_kelas,
                            'nama_kelas' => $kelasMatkul->nama_kelas,
                            'kode_rombel' => $kelasMatkul->kode_rombel ?? '-',
                            'kelas_clash' => 0,
                            'jumlah_mahasiswa' => $kelasMatkul->jumlah_mahasiswa,
                            'jumlah_sks' => $jumlahSks,
                            'jam_selesai' => $jamSelesaiFinal,
                            'time_invalid' => $isTimeInvalid,
                            'nama_ruang' => [
                                'kode' => $ruang->nama_ruang,
                                'kode_ruang' => $ruang->kode_ruang,
                                'kapasitas' => $ruang->kapasitas,
                                'capacity_invalid' => $isCapacityInvalid,
                                'room_type_mismatch' => $roomTypeMismatch,
                                'clash' => 0
                            ],
                            'kode_hari' => $hari->kode_hari,
                            'kode_jam' => $jamMulaiFinal,
                            'jam_mulai' => $jamMulaiFinal,
                            'jenis_matkul' => $jenisMatkul
                        ];
                    }
                }

                // Check overlaps for Dosen and Rombel
                $length = count($individuWithDetail[$i]);
                for ($a = 0; $a < $length; $a++) {
                    if (!isset($individuWithDetail[$i][$a])) {
                        continue;
                    }
                    for ($b = $a + 1; $b < $length; $b++) {
                        if (!isset($individuWithDetail[$i][$b])) {
                            continue;
                        }
                        $rowA = $individuWithDetail[$i][$a];
                        $rowB = $individuWithDetail[$i][$b];

                        if ($rowA['kode_hari'] !== $rowB['kode_hari']) {
                            continue;
                        }

                        $mulaiA = $this->jamToMinutes($rowA['jam_mulai']);
                        $selesaiA = $this->jamToMinutes($rowA['jam_selesai']);
                        $mulaiB = $this->jamToMinutes($rowB['jam_mulai']);
                        $selesaiB = $this->jamToMinutes($rowB['jam_selesai']);

                        $waktuBentrok = $mulaiA < $selesaiB && $mulaiB < $selesaiA;
                        if (!$waktuBentrok) {
                            continue;
                        }

                        if ($this->isDosenBentrok($rowA['kode_dosen']['list'], $rowB['kode_dosen']['list'])) {
                            $individuWithDetail[$i][$a]['kode_dosen']['clash'] = 1;
                            $individuWithDetail[$i][$b]['kode_dosen']['clash'] = 1;
                        }

                        if ($rowA['kode_rombel'] !== '-' && $rowA['kode_rombel'] === $rowB['kode_rombel']) {
                            $individuWithDetail[$i][$a]['kelas_clash'] = 1;
                            $individuWithDetail[$i][$b]['kelas_clash'] = 1;
                        }
                    }
                }

                ksort($individuWithDetail[$i]);
            }

            return $individuWithDetail;
        };

        $codeIntoNameIndividuDetail = function ($individuWithDetail) {
            $codeIntoNameIndividuDetail = [];

            for ($i = 0; $i < count($individuWithDetail); $i++) {
                for ($j = 0; $j < count($individuWithDetail[$i]); $j++) {
                    $row = $individuWithDetail[$i][$j];

                    $matkulNama = null;
                    $hariNama = null;
                    $jamValue = null;

                    if ($this->cacheLoaded) {
                        $mk = $this->getCachedMatkul($row['kode_matkul']);
                        $matkulNama = $mk ? $mk->nama_matkul : null;
                        $hr = $this->getCachedHari($row['kode_hari']);
                        $hariNama = $hr ? $hr->nama_hari : null;
                        $jm = $this->getCachedJam($row['kode_jam']);
                        $jamValue = $jm ? $jm->jam : ($row['jam_mulai'] ?? null);
                    } else {
                        $matkulNama = DB::table('matkul')->where('kode_matkul', $row['kode_matkul'])->value('nama_matkul');
                        $hariNama = DB::table('hari')->where('kode_hari', $row['kode_hari'])->value('nama_hari');
                        // jam table removed - use jam_mulai stored in row
                        $jamValue = $row['jam_mulai'] ?? null;
                    }

                    $codeIntoNameIndividuDetail[$i][$j] = [
                        'kode_kelas' => $row['id_kelas'],
                        'matkul' => $matkulNama,
                        'dosen' => $this->getNamaDosenGabungan($row['kode_dosen']['list']),
                        'kelas' => $row['nama_kelas'],
                        'kode_rombel' => $row['kode_rombel'],
                        'jumlah_sks' => $row['jumlah_sks'],
                        'jumlah_mahasiswa' => $row['jumlah_mahasiswa'],
                        'nama_ruang' => $row['nama_ruang']['kode'],
                        'kapasitas_ruang' => $row['nama_ruang']['kapasitas'],
                        'hari' => $hariNama,
                        'jam' => $jamValue,
                    ];
                }
            }

            return $codeIntoNameIndividuDetail;
        };

        $fitness = function ($individuWithDetail) {
            $fitness_function = [];
            $CD = [];
            $CR = [];
            $CK = [];

            for ($i = 0; $i < count($individuWithDetail); $i++) {
                $CD[$i] = 0;
                $CR[$i] = 0;
                $CK[$i] = 0;

                for ($j = 0; $j < count($individuWithDetail[$i]); $j++) {
                    if ($individuWithDetail[$i][$j]['kode_dosen']['clash'] == 1) {
                        $CD[$i] += 300;
                    }

                    if ($individuWithDetail[$i][$j]['nama_ruang']['clash'] == 1) {
                        $CR[$i] += 300;
                    }

                    if (($individuWithDetail[$i][$j]['kelas_clash'] ?? 0) == 1) {
                        $CK[$i] += 300;
                    }

                    if (($individuWithDetail[$i][$j]['kode_dosen']['blocked'] ?? 0) == 1) {
                        $CD[$i] += 300;
                    }

                    if (($individuWithDetail[$i][$j]['nama_ruang']['capacity_invalid'] ?? 0) == 1) {
                        $CR[$i] += 300;
                    }

                    if (($individuWithDetail[$i][$j]['time_invalid'] ?? 0) == 1) {
                        $CK[$i] += 150;
                    }

                    // Penalty jika tipe ruang tidak cocok dengan jenis mata kuliah
                    if (($individuWithDetail[$i][$j]['nama_ruang']['room_type_mismatch'] ?? 0) == 1) {
                        $CR[$i] += 300;
                    }

                    // Penalty jika praktikum tidak di hari sabtu/minggu (day >= 6), atau teori di weekend
                    $jenisMatkulTemp = $individuWithDetail[$i][$j]['jenis_matkul'] ?? 'teori';
                    $dayTemp = $individuWithDetail[$i][$j]['kode_hari'] ?? 1;
                    if ($jenisMatkulTemp === 'praktikum') {
                        if ($dayTemp < 6) {
                            $CK[$i] += 150;
                        }
                    } else {
                        if ($dayTemp >= 6) {
                            $CK[$i] += 150;
                        }
                    }

                    // Penalty jika matkul 3 SKS tidak di pagi hari
                    if (($individuWithDetail[$i][$j]['jumlah_sks'] ?? 0) == 3) {
                        $jamMulaiTemp = $individuWithDetail[$i][$j]['jam_mulai'] ?? null;
                        if ($jamMulaiTemp && !$this->isSlotPagi($jamMulaiTemp)) {
                            $CK[$i] += 300;
                        }
                    }
                }

                $CK[$i] += $this->hitungPenaltySebaranHari($individuWithDetail[$i]);

                $CD[$i] = (int) ceil($CD[$i] / 2);
                $CR[$i] = (int) ceil($CR[$i] / 2);
                $CK[$i] = (int) ceil($CK[$i] / 2);
            }

            $fitness_function["CD"] = $CD;
            $fitness_function["CR"] = $CR;
            $fitness_function["CK"] = $CK;

            $fitnessIndividu = [];
            $total_nilai_fitness = 0;

            for ($i = 0; $i < count($individuWithDetail); $i++) {
                $fitnessIndividu[$i] = 1 / (1 + ($CD[$i] + $CR[$i] + $CK[$i]));
                $total_nilai_fitness += $fitnessIndividu[$i];
            }

            $fitness_function["fitness_individu"] = $fitnessIndividu;
            $fitness_function["total_fitness"] = $total_nilai_fitness;

            $hasOne = array_keys($fitnessIndividu, 1);
            $fixJadwal = [];

            if ($hasOne) {
                for ($i = 0; $i < count($hasOne); $i++) {
                    $fixJadwal[$i] = $individuWithDetail[$hasOne[$i]];
                }
            }

            $fitness_function["fix_jadwal"] = $fixJadwal;

            return $fitness_function;
        };

        $allClashChromosome = function ($individu, $precomputedDetail = null) use ($individuWithDetail) {
            $detail = $precomputedDetail !== null ? $precomputedDetail : $individuWithDetail($individu);
            $allClashChromosome = [];

            for ($i = 0; $i < count($detail); $i++) {
                for ($j = 0; $j < count($detail[$i]); $j++) {
                    if (
                        $detail[$i][$j]["kode_dosen"]["clash"] == 1 ||
                        $detail[$i][$j]["nama_ruang"]["clash"] == 1 ||
                        ($detail[$i][$j]["kelas_clash"] ?? 0) == 1 ||
                        $detail[$i][$j]["kode_dosen"]["blocked"] == 1 ||
                        $detail[$i][$j]["nama_ruang"]["capacity_invalid"] == 1 ||
                        ($detail[$i][$j]["nama_ruang"]["room_type_mismatch"] ?? 0) == 1 ||
                        ($detail[$i][$j]["time_invalid"] ?? 0) == 1
                    ) {
                        $allClashChromosome[] = [
                            "kromosom" => $individu[$i][$j],
                            "index_individu" => $i,
                            "index_kromosom" => $j
                        ];
                    }
                }
            }

            return $allClashChromosome;
        };

        $individu = [];

        for ($i = 0; $i < $jumlahIndividu; $i++) {
            $individu[$i] = [];

            foreach ($kelasMatkulTable as $indexKelas => $kelasMatkul) {
                $prioritas = null;

                foreach ($prioritas_kelas as $p) {
                    if ($kelasMatkul->id_kelas == $p['id_kelas']) {
                        $prioritas = $p;
                        break;
                    }
                }

                $jenisMatkul = $this->getJenisMatkulByKelasMatkul($kelasMatkul);
                $namaMatkul = $this->getNamaMatkulByKelasMatkul($kelasMatkul);

                $kodeRuang = $randomKodeRuang(
                    $kelasMatkul->kode_prodi,
                    $kelasMatkul->jumlah_mahasiswa,
                    $jenisMatkul,
                    $namaMatkul
                );

                $jumlahSksKelas = $this->getJumlahSksByKelasMatkul($kelasMatkul);

                if ($prioritas && isset($prioritas['kode_hari'])) {
                    $kodeHari = $prioritas['kode_hari'];
                } else {
                    if ($jenisMatkul === 'praktikum') {
                        $kodeHari = mt_rand(6, 7);
                    } else {
                        $kodeHari = $kodeHariAktifKuliah[mt_rand(0, count($kodeHariAktifKuliah) - 1)];
                    }
                }

                $individu[$i][] = [
                    $kelasMatkul->id_kelas,
                    $kodeRuang,
                    $kodeHari
                ];
            }

            // Repair awal hanya untuk sebagian populasi.
            // Kalau semua individu langsung direpair, populasi menjadi terlalu mirip dan hasil akhir cenderung sama terus.
            $jumlahRepairAwal = max(5,(int) ceil($jumlahIndividu * 0.75));
            if ($i < $jumlahRepairAwal || mt_rand(1, 100) <= 15) {
                $individu[$i] = $this->repairIndividuMinBentrok($individu[$i], $prioritas_kelas, false);
            }
        }

        $algoritma_proses = [];
        $time_start = microtime(true);

        // Target praktis: request diberi ruang lebih panjang sedikit agar repair operator sempat menekan bentrok.
        // Jika fitness sempurna belum ditemukan, sistem mengembalikan individu terbaik hasil GA.
        $batasWaktuGenerateDetik = 270;
        $berhentiKarenaBatasWaktu = false;

        $fixJadwal = [];
        $bestJadwal = [];
        $bestIndividu = [];
        $bestFitness = 0;
        $isFallback = false;
        $generasi = 0;
        $generasiTanpaPerbaikan = 0;
        // Agar generate dengan setting besar tidak membuat memory penuh,
        // detail proses algoritma hanya disimpan jika checkbox "Tampilkan Proses Algoritma" dicentang.
        // Untuk keamanan performa, detail proses dibatasi 20 generasi pertama.
        $simpanProsesAlgoritma = !empty($showAlgorithm);
        $maxProsesDetail = 20;

        while ($generasi < $maxGenerasi && count($fixJadwal) == 0) {
            if ((microtime(true) - $time_start) >= $batasWaktuGenerateDetik) {
                $berhentiKarenaBatasWaktu = true;
                break;
            }

            $detail = $individuWithDetail($individu);

            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["individu"] = $individu;
            }
            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["individuWithDetail"] = $detail;
            }
            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["individuWithDetail_with_name"] = $codeIntoNameIndividuDetail($detail);
            }

            $fitness_function = $fitness($detail);
            $CD = $fitness_function['CD'];
            $CR = $fitness_function['CR'];
            $CK = $fitness_function['CK'];
            $fitnessIndividu = $fitness_function['fitness_individu'];
            $total_nilai_fitness = $fitness_function['total_fitness'];
            $fixJadwal = $fitness_function['fix_jadwal'];

            $maxFitness = max($fitnessIndividu);
            $bestIndex = array_search($maxFitness, $fitnessIndividu);

            if ($maxFitness > $bestFitness) {
                $bestFitness = $maxFitness;
                $bestJadwal = [$detail[$bestIndex]];
                $bestIndividu = $individu[$bestIndex] ?? [];
                $generasiTanpaPerbaikan = 0;
            } else {
                $generasiTanpaPerbaikan++;
            }

            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["CD"] = $CD;
            }
            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["CR"] = $CR;
            }
            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["CK"] = $CK;
            }
            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["fitness_individu"] = $fitnessIndividu;
            }
            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["total_fitness"] = $total_nilai_fitness;
            }
            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["fix_jadwal"] = $fixJadwal;
            }

            if ($fixJadwal) {
                break;
            }

            $probabilitas = [];
            for ($i = 0; $i < count($fitnessIndividu); $i++) {
                $probabilitas[$i] = $total_nilai_fitness > 0
                    ? $fitnessIndividu[$i] / $total_nilai_fitness
                    : 0;
            }

            $kumulatif = [];
            $total_kumulatif = 0;

            for ($i = 0; $i < count($probabilitas); $i++) {
                $kumulatif[$i] = $probabilitas[$i] + $total_kumulatif;
                $total_kumulatif = $kumulatif[$i];
            }

            $random = $random_1($individu);
            $newIndividu = [];
            $listNewIndividu = [];

            for ($i = 0; $i < count($individu); $i++) {
                for ($j = 0; $j < count($random); $j++) {
                    $newIndividu[$i] = $random[$i] <= $kumulatif[$j] ? $individu[$j] : [];

                    if ($newIndividu[$i]) {
                        $listNewIndividu[] = $j;
                        break;
                    }
                }

                if (!$newIndividu[$i]) {
                    $newIndividu[$i] = $individu[$i];
                }
            }

            // Elitism: individu terbaik tidak boleh hilang setelah selection/crossover/mutation.
            if (count($bestIndividu) > 0) {
                $newIndividu[0] = $bestIndividu;
            }

            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["probabilitas"] = $probabilitas;
            }
            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["kumulatif"] = $kumulatif;
            }
            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["total_kumulatif"] = $total_kumulatif;
            }
            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["random1_selection"] = $random;
            }
            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["list_new_individu_selection"] = $listNewIndividu;
            }
            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["new_individu_selection"] = $newIndividu;
            }

            $PC = $crossoverRate / 75;
            $indexIndividuSelected = [];
            $random = $random_1($individu);

            for ($i = 0; $i < count($random); $i++) {
                if ($random[$i] < $PC) {
                    $indexIndividuSelected[] = $i;
                }
            }

            if (count($indexIndividuSelected) < 3) {
                $remainingIndex = array_values(array_diff(range(0, count($individu) - 1), $indexIndividuSelected));
                shuffle($remainingIndex);

                $indexIndividuSelected = array_merge(
                    $indexIndividuSelected,
                    array_slice($remainingIndex, 0, 3 - count($indexIndividuSelected))
                );
            }

            $parents = [];

            for ($i = 0; $i < count($indexIndividuSelected); $i++) {
                $father = $indexIndividuSelected[$i];
                $mother = ($i == count($indexIndividuSelected) - 1)
                    ? $indexIndividuSelected[0]
                    : $indexIndividuSelected[$i + 1];

                $parents[$i] = [
                    'father' => $father,
                    'mother' => $mother,
                    'cut-point' => $random_2($individu[0]),
                ];
            }

            $offSpring = [];

            for ($i = 0; $i < count($parents); $i++) {
                $fatherChromosome = $newIndividu[$parents[$i]['father']];
                $motherChromosome = $newIndividu[$parents[$i]['mother']];
                $cutPoint = $parents[$i]['cut-point'];

                $offSpring[$i] = array_merge(
                    array_slice($fatherChromosome, 0, $cutPoint),
                    array_slice($motherChromosome, $cutPoint)
                );
            }

            for ($i = 0; $i < count($indexIndividuSelected); $i++) {
                $newIndividu[$indexIndividuSelected[$i]] = $offSpring[$i];
            }

            $detail = $individuWithDetail($newIndividu);
            $fitness_function = $fitness($detail);

            $CD = $fitness_function['CD'];
            $CR = $fitness_function['CR'];
            $CK = $fitness_function['CK'];
            $fitnessIndividu = $fitness_function['fitness_individu'];
            $total_nilai_fitness = $fitness_function['total_fitness'];
            $fixJadwal = $fitness_function['fix_jadwal'];

            $maxFitness = max($fitnessIndividu);
            $bestIndex = array_search($maxFitness, $fitnessIndividu);

            if ($maxFitness > $bestFitness) {
                $bestFitness = $maxFitness;
                $bestJadwal = [$detail[$bestIndex]];
                $bestIndividu = $newIndividu[$bestIndex] ?? [];
                $generasiTanpaPerbaikan = 0;
            }

            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["PC"] = $PC;
            }
            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["random1_crossover"] = $random;
            }
            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["index_best_individu"] = $indexIndividuSelected;
            }
            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["parents"] = $parents;
            }
            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["offSpring"] = $offSpring;
            }
            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["new_individu_crossover"] = $newIndividu;
            }
            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["new_individu_crossover_with_detail"] = $detail;
            }
            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["new_CD"] = $CD;
            }
            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["new_CR"] = $CR;
            }
            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["new_CK"] = $CK;
            }
            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["new_fitness_individu"] = $fitnessIndividu;
            }
            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["new_total_fitness"] = $total_nilai_fitness;
            }
            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["new_fix_jadwal"] = $fixJadwal;
            }

            if ($fixJadwal) {
                break;
            }

            $allClash = $allClashChromosome($newIndividu, $detail);

            // Pada setting besar, jumlah kromosom bentrok bisa sangat banyak.
            // Mutasi tetap dilakukan pada kromosom bermasalah, tetapi dibatasi agar GA tidak meledak waktu prosesnya.
            $maksMutasiPerGenerasi = max($jumlahIndividu * 3, 30);
            if (count($allClash) > $maksMutasiPerGenerasi) {
                shuffle($allClash);
                $allClash = array_slice($allClash, 0, $maksMutasiPerGenerasi);
            }

            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["all_clash_chromosome"] = $allClash;
            }

            for ($i = 0; $i < count($allClash); $i++) {
                $idKelas = $allClash[$i]['kromosom'][0];

                $kelasMatkul = $this->getCachedKelasMatkul($idKelas);

                if (!$kelasMatkul) {
                    continue;
                }

                $jumlahSksKelas = $this->getJumlahSksByKelasMatkul($kelasMatkul);
                $jenisMatkulMutasi = $this->getJenisMatkulByKelasMatkul($kelasMatkul);
                if ($jenisMatkulMutasi === 'praktikum') {
                    $targetKodeHariMutasi = mt_rand(6, 7);
                } else {
                    $targetKodeHariMutasi = $kodeHariAktifKuliah[mt_rand(0, count($kodeHariAktifKuliah) - 1)];
                }

                foreach ($prioritas_kelas as $p) {
                    if ($idKelas == $p['id_kelas'] && isset($p['kode_hari'])) {
                        $targetKodeHariMutasi = $p['kode_hari'];
                        break;
                    }
                }

                $namaMatkulMutasi = $this->getNamaMatkulByKelasMatkul($kelasMatkul);
                $mutatedChro = [
                    $idKelas,
                    $randomKodeRuang($kelasMatkul->kode_prodi, $kelasMatkul->jumlah_mahasiswa, $jenisMatkulMutasi, $namaMatkulMutasi),
                    $targetKodeHariMutasi
                ];

                if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                    $algoritma_proses[$generasi]["mutated_chromosome"][$i] = $mutatedChro;
                }
                $newIndividu[$allClash[$i]['index_individu']][$allClash[$i]['index_kromosom']] = $mutatedChro;
            }

            // Repair operator: setelah mutasi, perbaiki individu yang masih memiliki kromosom bermasalah.
            // Ini tetap bagian dari GA, bukan override final, karena hanya memperbaiki offspring/mutant.
            $indexIndividuRepair = [];
            foreach ($allClash as $clashRow) {
                if (isset($clashRow['index_individu'])) {
                    $indexIndividuRepair[] = $clashRow['index_individu'];
                }
            }
            $indexIndividuRepair = array_values(array_unique($indexIndividuRepair));

            // Repair tetap dipakai sebagai operator GA, tetapi dibatasi agar setting 50/500/75 tidak timeout.
            $maksRepairIndividuPerGenerasi = min(25,max(5,(int) ceil($jumlahIndividu * 0.25)));
            if (count($indexIndividuRepair) > $maksRepairIndividuPerGenerasi) {
                shuffle($indexIndividuRepair);
                $indexIndividuRepair = array_slice($indexIndividuRepair, 0, $maksRepairIndividuPerGenerasi);
            }

            foreach ($indexIndividuRepair as $idxRepair) {
                if (isset($newIndividu[$idxRepair])) {
                    $newIndividu[$idxRepair] = $this->repairIndividuMinBentrok($newIndividu[$idxRepair], $prioritas_kelas, false);
                }
            }

            if ($simpanProsesAlgoritma && $generasi < $maxProsesDetail) {
                $algoritma_proses[$generasi]["new_individu_has_mutated"] = $newIndividu;
            }

            // Jika GA stagnan, suntik beberapa individu acak agar tidak terjebak pada pola bentrok yang sama.
            if ($generasiTanpaPerbaikan >= 35 && count($kelasMatkulTable) > 0) {
                $jumlahImmigrant = max(2, (int) ceil($jumlahIndividu * 0.12));
                for ($imm = 0; $imm < $jumlahImmigrant; $imm++) {
                    $idxImmigrant = mt_rand(1, max(1, $jumlahIndividu - 1));
                    $randomChromosomes = [];
                    foreach ($kelasMatkulTable as $kelasMatkulImm) {
                        $jumlahSksImm = $this->getJumlahSksByKelasMatkul($kelasMatkulImm);
                        $jenisMatkulImm = $this->getJenisMatkulByKelasMatkul($kelasMatkulImm);
                        if ($jenisMatkulImm === 'praktikum') {
                            $kodeHariImm = mt_rand(6, 7);
                        } else {
                            $kodeHariImm = $kodeHariAktifKuliah[mt_rand(0, count($kodeHariAktifKuliah) - 1)];
                        }
                        $kodeWaktuImm = $this->randomKodeWaktuValidBySksAndHari($jumlahSksImm, $kodeHariImm);
                        if (!$kodeWaktuImm) {
                            continue;
                        }
                        $namaMatkulImm = $this->getNamaMatkulByKelasMatkul($kelasMatkulImm);
                        $randomChromosomes[] = [
                            $kelasMatkulImm->id_kelas,
                            $randomKodeRuang($kelasMatkulImm->kode_prodi, $kelasMatkulImm->jumlah_mahasiswa, $jenisMatkulImm, $namaMatkulImm),
                            $kodeWaktuImm
                        ];
                    }
                    if (count($randomChromosomes) > 0) {
                        $newIndividu[$idxImmigrant] = mt_rand(1, 100) <= 50
                            ? $this->repairIndividuMinBentrok($randomChromosomes, $prioritas_kelas, false)
                            : $randomChromosomes;
                    }
                }
                $generasiTanpaPerbaikan = 0;
            }

            // Jaga kembali elite setelah proses mutasi dan repair.
            if (count($bestIndividu) > 0) {
                // Elitism: bawa individu terbaik apa adanya.
                // Jangan repair elite setiap generasi karena itu membuat proses sangat lambat.
                $newIndividu[0] = $bestIndividu;
            }

            $individu = $newIndividu;
            $generasi++;
        }

        // Repair final agresif pada individu terbaik hasil GA.
        // Dicoba beberapa kali dengan variasi acak agar sisa bentrok tidak selalu berada pada mata kuliah/ruang/jam yang sama.
        if (count($bestIndividu) > 0) {
            $jumlahPercobaanFinalRepair = 50;
            $bestFinalFitness = $bestFitness;
            $bestFinalIndividu = $bestIndividu;
            $bestFinalJadwal = $bestJadwal;
            $bestFinalFix = $fixJadwal;

            for ($attemptRepair = 0; $attemptRepair < $jumlahPercobaanFinalRepair; $attemptRepair++) {
                if ((microtime(true) - $time_start) >= ($batasWaktuGenerateDetik + 35)) {
                    break;
                }

                // Reset cache dosen tiap percobaan final agar pilihan 2 dosen acak dari daftar pengampu bisa berubah.
                // Ini membantu menghindari bentrok dosen yang terus jatuh pada pasangan dosen yang sama.
                $this->cacheDosenListByIdKelas = [];

                $candidateIndividu = $this->repairIndividuMinBentrok($bestIndividu, $prioritas_kelas, true);
                $candidateDetail = $individuWithDetail([$candidateIndividu]);
                $candidateFitness = $fitness($candidateDetail);
                $candidateFitnessValue = $candidateFitness['fitness_individu'][0] ?? 0;

                if (!isset($candidateDetail[0])) {
                    continue;
                }

                if (
                    $candidateFitnessValue > $bestFinalFitness ||
                    ($candidateFitnessValue == $bestFinalFitness && mt_rand(1, 100) <= 35)
                ) {
                    $bestFinalFitness = $candidateFitnessValue;
                    $bestFinalIndividu = $candidateIndividu;
                    $bestFinalJadwal = [$candidateDetail[0]];
                    $bestFinalFix = count($candidateFitness['fix_jadwal'] ?? []) > 0
                        ? $candidateFitness['fix_jadwal']
                        : $bestFinalFix;
                }

                if ($candidateFitnessValue >= 1) {
                    break;
                }
            }

            $bestIndividu = $bestFinalIndividu;
            $bestFitness = $bestFinalFitness;
            $bestJadwal = $bestFinalJadwal;
            if (count($bestFinalFix) > 0) {
                $fixJadwal = $bestFinalFix;
                $isFallback = false;
            }
        }

        $execution_time = microtime(true) - $time_start;

        if (count($fixJadwal) == 0 && count($bestJadwal) > 0) {
            $fixJadwal = $bestJadwal;
            $isFallback = true;

            if ($berhentiKarenaBatasWaktu) {
                Session::flash('status', 'Generate dihentikan otomatis mendekati batas waktu aman. Sistem menampilkan jadwal terbaik dari proses algoritma genetika.');
            } else {
                Session::flash('status', 'Jadwal sempurna belum ditemukan, tetapi sistem menampilkan jadwal terbaik yang ditemukan.');
            }
        }

        if (count($fixJadwal) == 0) {
            Session::flash('status', 'Generate belum menghasilkan jadwal. Pastikan setiap mata kuliah memiliki minimal 2 kandidat dosen, data ruang tersedia, dan data waktu valid.');
        }

        // PENTING UNTUK SKRIPSI:
        // Hasil akhir tidak lagi dioverride memakai jadwal deterministik/greedy.
        // Jadwal yang ditampilkan adalah individu terbaik hasil proses GA murni
        // dari populasi, fitness, selection, crossover, mutation, dan elitism.
        if (!$showAlgorithm) {
            $algoritma_proses = [];
        }

        // Simpan data ke session DI CONTROLLER (bukan di Blade view).
        // Ini lebih aman dan menghindari masalah timing/race condition session.
        $fixJadwalSiapPakai = [];
        if (isset($fixJadwal) && is_array($fixJadwal) && count($fixJadwal) > 0) {
            $fixJadwalSiapPakai = $fixJadwal;
        }

        if (count($fixJadwalSiapPakai) > 0) {
            Session::put('jadwal', $fixJadwalSiapPakai);
            Session::put('kodeSemester', $kodeSemester);
            Session::put('tahunAjaran', $tahunAjaran);
        }

        return view('penjadwalankuliah.generatejadwal', compact(
            'user_login',
            'semester',
            'algoritma_proses',
            'execution_time',
            'fixJadwal',
            'kodeSemester',
            'countRequest',
            'tahunAjaran',
            'allTahunAjaran',
            'allDosen',
            'allHari',
            'countKuliahTabel',
            'isFallback'
        ));
    }

    public function hasilgenerate(Request $request, $jadwal_index)
    {
        $allJadwal = $request->session()->get('jadwal');
        $kode_semester = $request->session()->get('kodeSemester');
        $tahun_ajaran = trim((string) $request->session()->get('tahunAjaran'));

        // Validasi session data
        if (!$kode_semester || !$tahun_ajaran) {
            return redirect('/generatejadwal')->with('status', 'Sesi generate telah kedaluwarsa. Silakan generate ulang jadwal.');
        }

        $nama_semester = DB::table('semester')
            ->where('kode_semester', $kode_semester)
            ->value('nama_semester');

        if (!$nama_semester) {
            return redirect('/generatejadwal')->with('status', 'Data semester tidak valid. Silakan generate ulang jadwal.');
        }

        if (!$allJadwal || !isset($allJadwal[$jadwal_index])) {
            return redirect('/hasiljadwal')->with('status', 'Data jadwal tidak ditemukan.');
        }

        // Simpan jadwal hasil GA dari session.
        // Jangan membangun ulang jadwal deterministik di sini, supaya hasil yang masuk database
        // tetap sama dengan hasil GA yang ditampilkan pada halaman generate.
        $fixJadwal = $allJadwal[$jadwal_index];

        $jadwalLamaIds = DB::table('jadwal')
            ->where('semester', $nama_semester)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->pluck('id')
            ->toArray();

        if (count($jadwalLamaIds) > 0) {
            DB::table('jadwal_dosen')
                ->whereIn('jadwal_id', $jadwalLamaIds)
                ->delete();
        }

        DB::table('jadwal')
            ->where('semester', $nama_semester)
            ->where('tahun_ajaran', $tahun_ajaran)
            ->delete();

        if (!DB::table('tahun_ajaran')->where('tahun_ajaran', $tahun_ajaran)->first()) {
            DB::table('tahun_ajaran')->insert([
                'tahun_ajaran' => $tahun_ajaran,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        foreach ($fixJadwal as $row) {
            $jam_masuk = $row['jam_mulai'] ?? (isset($row['kode_jam']) && strpos($row['kode_jam'], ':') !== false ? substr($row['kode_jam'], 0, 5) : '07:00');
            $jam_keluar = $row['jam_selesai'] ?? $this->hitungJamSelesai($jam_masuk, $row['jumlah_sks']);

            $kodeDosenList = $row['kode_dosen']['list'] ?? [$row['kode_dosen']['kode']];
            $kodeDosenList = array_values(array_unique(array_filter($kodeDosenList)));

            if (count($kodeDosenList) < 1) {
                continue;
            }

            $kodeDosenGabungan = implode(', ', $kodeDosenList);

            $matkulRecord = DB::table('matkul')->where('kode_matkul', $row['kode_matkul'])->first();

            $jadwalId = DB::table('jadwal')->insertGetId([
                'matkul' => $matkulRecord->nama_matkul ?? $row['kode_matkul'],
                'jenis_matkul' => $matkulRecord->jenis_matkul ?? 'teori',
                'tipe_matkul' => $matkulRecord->tipe_matkul ?? 'wajib',
                'dosen' => $kodeDosenGabungan,
                'kelas' => $row['nama_kelas'],
                'jumlah_sks' => $row['jumlah_sks'],
                'nama_ruang' => $row['nama_ruang']['kode'],
                'hari' => DB::table('hari')->where('kode_hari', $row['kode_hari'])->value('nama_hari'),
                'jam_masuk' => $jam_masuk,
                'jam_keluar' => $jam_keluar,
                'semester' => $nama_semester,
                'tahun_ajaran' => $tahun_ajaran
            ]);

            foreach ($kodeDosenList as $kodeDosen) {
                DB::table('jadwal_dosen')->insert([
                    'jadwal_id' => $jadwalId,
                    'kode_dosen' => $kodeDosen,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect('/hasiljadwal')->with('selected_tahun_ajaran', $tahun_ajaran);
    }

    public function hasiljadwal(Request $request)
    {
        $user_login = $request->session()->get('user_login');

        $countRequest = DB::table('request_kuliah')->count()
            + DB::table('request_ruang')->count()
            + DB::table('request_waktu')->count();

        $semester = DB::table('semester')->get();
        $jadwal = [];

        for ($i = 0; $i < count($semester); $i++) {
            $jadwal[$i] = DB::table('jadwal')
                ->where('semester', $semester[$i]->nama_semester)
                ->orderByDesc('tahun_ajaran')
                ->orderByRaw("FIELD(LOWER(REPLACE(hari, CHAR(39), '')), 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu')")
                ->orderBy('jam_masuk')
                ->orderBy('kelas')
                ->get();

            foreach ($jadwal[$i] as $row) {
                $kodeDosenRelasi = DB::table('jadwal_dosen')
                    ->where('jadwal_id', $row->id)
                    ->orderBy('id')
                    ->pluck('kode_dosen')
                    ->toArray();

                if (count($kodeDosenRelasi) > 0) {
                    $row->dosen = implode(', ', $kodeDosenRelasi);
                }
            }
        }

        // Gabungkan tahun_ajaran dari tabel tahun_ajaran DAN dari tabel jadwal.
        // Ini memastikan tahun yang baru di-generate pasti muncul di dropdown,
        // meskipun ada ketidakcocokan data di tabel tahun_ajaran.
        $tahunDariTabel = DB::table('tahun_ajaran')
            ->pluck('tahun_ajaran')
            ->toArray();

        $tahunDariJadwal = DB::table('jadwal')
            ->distinct()
            ->pluck('tahun_ajaran')
            ->toArray();

        $semuaTahun = array_values(array_unique(array_merge($tahunDariTabel, $tahunDariJadwal)));
        rsort($semuaTahun);

        $tahun_ajaran = collect($semuaTahun)->map(function ($ta) {
            return (object) ['tahun_ajaran' => $ta];
        });

        $selected_tahun_ajaran = session('selected_tahun_ajaran', '');

        // Ambil data semua dosen untuk ditampilkan di halaman hasil jadwal
        $dosen = DB::table('dosen')->orderBy('kode_dosen')->get();

        return view('penjadwalankuliah.hasiljadwal', compact(
            'user_login',
            'jadwal',
            'countRequest',
            'semester',
            'tahun_ajaran',
            'selected_tahun_ajaran',
            'dosen'
        ));
    }
}