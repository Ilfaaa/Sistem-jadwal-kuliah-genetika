<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Dosen;
use App\Models\Prodi;

class ManagedosenController extends Controller
{
    private function normalizeText($text)
    {
        $text = trim($text);
        $text = preg_replace('/\s+/', ' ', $text);
        return $text;
    }

    private function normalizeName($name)
{
    $name = $this->normalizeText($name);
    $name = ucwords(strtolower($name));

    // Perbaikan khusus gelar
    $replace = [
        'S.kom.' => 'S.Kom.',
        'M.t.' => 'M.T.',
        'Ph.d.' => 'Ph.D.',
        'S.t.' => 'S.T.',
        'M.kom.' => 'M.Kom.',
        'Ipu.' => 'IPU.',
        'Asean Eng.' => 'ASEAN Eng.',
        'M.i.t.' => 'M.I.T.',
        'M.eng.' => 'M.Eng.',
        'S.si.' => 'S.Si.',
        'M.si.' => 'M.Si.',
        'M.cs.' => 'M.Cs.',
        'M.m.' => 'M.M.',
        'Dr.eng.' => 'Dr.Eng.',
        'F.med.' => 'F.Med.',
        'M.sc.' => 'M.Sc.',
        'S.ag.' => 'S.Ag.',
        'M.pd.' => 'M.Pd.',
        'M.hum.' => 'M.Hum.',
        'M.infotech.(comp).' => 'M.InfoTech.(Comp).',
        'M.pd.b.' => 'M.Pd.B.',
        'M.th.' => 'M.Th.',
        'S.s.' => 'S.S.',
        'ipm.' => 'IPM.'
        

        
    ];

     foreach ($replace as $key => $val) {
        $name = str_ireplace($key, $val, $name);
    }
    return $name;
}
    public function index(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();
        $request_keyword = "";

        if ($request->keyword) {
            $keyword = $request->keyword;
            // Kode Aman (Nested Query Builder & Parameter Binding) setelah Refactoring (Pengujian Tahap II)
            $dosen = Dosen::where(function($q) use ($keyword) {
                $q->where('nama', 'LIKE', '%' . $keyword . '%')
                  ->orWhere('program_studi', 'LIKE', '%' . $keyword . '%')
                  ->orWhere('kode_dosen', 'LIKE', '%' . $keyword . '%')
                  ->orWhere('nidn', 'LIKE', '%' . $keyword . '%');
            })->get();

            $request_keyword = $request->keyword;
        } else {
            $dosen = Dosen::get();
        }

        return view('managedosen.index', compact('dosen', 'user_login', 'request_keyword', 'countRequest'));
    }

    public function create(Request $request)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();
        $prodi = Prodi::get();

        return view('managedosen.create', compact('user_login', 'prodi', 'countRequest'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'kode_dosen' => ['required', 'max:30', 'regex:/^[A-Za-z0-9]+$/', 'unique:dosen,kode_dosen'],
                'nama' => 'required|min:3|max:255',
                'nidn' => ['required', 'unique:dosen,nidn'],
                'program_studi' => 'required'
            ],
            [
                'kode_dosen.required' => 'Kode dosen wajib diisi.',
                'kode_dosen.max' => 'Kode dosen maksimal 30 karakter.',
                'kode_dosen.regex' => 'Kode dosen hanya boleh berisi huruf dan angka tanpa spasi.',
                'kode_dosen.unique' => 'Kode dosen sudah digunakan.',
                'nama.required' => 'Kolom nama harap di isi.',
                'nama.min' => 'Nama minimal 3 huruf.',
                'nama.max' => 'Nama maksimal 255 huruf.',
                'nidn.required' => 'NIDN Atau NIP Tidak Boleh Kosong.',
                'nidn.unique' => 'NIP/NIDN sudah digunakan oleh dosen lain.',
                'program_studi.required' => 'Harap pilih salah satu program studi.',
            ]
        );

        $user_login = $request->session()->get('user_login');

        $kodeDosen = strtoupper(trim($request->kode_dosen));
        $namaDosen = $this->normalizeName($request->nama);
        $programStudi = $this->normalizeText($request->program_studi);

        if ($user_login->role_id != '1') {
            DB::table('request_kuliah')->insert([
                'request' => 'Tambah Data',
                'manage' => 'Dosen',
                'kode_manage' => $kodeDosen . '-' . $request->nidn,
                'nama_manage' => $namaDosen,
                'sks' => '',
                'kode_prodi' => '',
                'kode_semester' => '',
                'nama_prodi' => $programStudi,
                'nama_matkul' => '',
                'nama_dosen' => '',
                'kapasitas_kelas' => 0,
                'name' => $user_login->name,
                'image' => $user_login->image,
                'created_at' => date("Y-m-d H:i:s")
            ]);

            return redirect('/managekuliah/managedosen')->with('status', 'Data dosen berhasil dikirimkan ke admin!');
        } else {
            DB::table('dosen')->insert([
                'kode_dosen' => $kodeDosen,
                'nidn' => $request->nidn,
                'nama' => $namaDosen,
                'program_studi' => $programStudi,
                'no_whatsapp' => $request->no_whatsapp,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect('/managekuliah/managedosen')->with('status', 'Data dosen berhasil ditambahkan!');
        }
    }

    public function edit(Request $request, $kode_dosen)
    {
        $user_login = $request->session()->get('user_login');
        $countRequest = DB::table('request_kuliah')->count() + DB::table('request_ruang')->count() + DB::table('request_waktu')->count();
        $dosen = DB::table('dosen')->where('kode_dosen', $kode_dosen)->first();
        $prodi = DB::table('prodi')->get();

        return view('managedosen.edit', compact('user_login', 'dosen', 'prodi', 'countRequest'));
    }

    public function update(Request $request, $kode_dosen)
    {
        $request->validate(
            [
                'kode_dosen' => [
                    'required',
                    'max:30',
                    'regex:/^[A-Za-z0-9]+$/',
                    Rule::unique('dosen', 'kode_dosen')->ignore($kode_dosen, 'kode_dosen')
                ],
                'nama' => 'required|min:3|max:255',
                'nidn' => [
                    'required',
                    Rule::unique('dosen', 'nidn')->ignore($kode_dosen, 'kode_dosen')
                ],
                'program_studi' => 'required'
            ],
            [
                'kode_dosen.required' => 'Kode dosen wajib diisi.',
                'kode_dosen.max' => 'Kode dosen maksimal 30 karakter.',
                'kode_dosen.regex' => 'Kode dosen hanya boleh berisi huruf dan angka tanpa spasi.',
                'kode_dosen.unique' => 'Kode dosen sudah digunakan.',
                'nama.required' => 'Kolom nama harap di isi.',
                'nama.min' => 'Nama minimal 3 huruf.',
                'nama.max' => 'Nama maksimal 255 huruf.',
                'nidn.required' => 'NIDN Atau NIP Tidak Boleh Kosong.',
                'nidn.unique' => 'NIP/NIDN sudah digunakan oleh dosen lain.',
                'program_studi.required' => 'Harap pilih salah satu program studi.',
            ]
        );

        $user_login = $request->session()->get('user_login');
        $dosen_old = DB::table('dosen')->where('kode_dosen', $kode_dosen)->first();

        $kodeDosenBaru = strtoupper(trim($request->kode_dosen));
        $namaDosenBaru = $this->normalizeName($request->nama);
        $programStudiBaru = $this->normalizeText($request->program_studi);

        if ($user_login->role_id != '1') {
            DB::table('request_kuliah')->insert([
                'request' => 'Ubah Data',
                'manage' => 'Dosen',
                'kode_manage' => $kodeDosenBaru . '-' . $dosen_old->kode_dosen . '-' . $request->nidn,
                'nama_manage' => $namaDosenBaru,
                'sks' => '',
                'kode_prodi' => '',
                'kode_semester' => '',
                'nama_prodi' => $programStudiBaru,
                'nama_matkul' => '',
                'nama_dosen' => '',
                'kapasitas_kelas' => 0,
                'name' => $user_login->name,
                'image' => $user_login->image,
                'created_at' => date("Y-m-d H:i:s")
            ]);

            return redirect('/managekuliah/managedosen')->with('status', 'Perubahan berhasil diajukan ke admin!');
        } else {
            DB::table('dosen')
                ->where('kode_dosen', $kode_dosen)
                ->update([
                    'kode_dosen' => $kodeDosenBaru,
                    'nama' => $namaDosenBaru,
                    'nidn' => $request->nidn,
                    'program_studi' => $programStudiBaru,
                    'no_whatsapp' => $request->no_whatsapp,
                    'updated_at' => now(),
                ]);

            DB::table('kelas')
                ->where('nama_dosen', $dosen_old->nama)
                ->update([
                    'nama_dosen' => $namaDosenBaru,
                ]);

            return redirect('/managekuliah/managedosen')->with('status', 'Data dosen berhasil diubah');
        }
    }

    public function destroy(Request $request, $kode_dosen)
    {
        $user_login = $request->session()->get('user_login');
        $all_dosen = DB::table('dosen')->get();

        if (count($all_dosen) == 1) {
            return redirect('managekuliah/managedosen')->with('status', 'Minimal tersisa satu dosen!');
        }

        $dosen = Dosen::where('kode_dosen', $kode_dosen)->first();

        if ($user_login->role_id != '1') {
            DB::table('request_kuliah')->insert([
                'request' => 'Hapus Data',
                'manage' => 'Dosen',
                'kode_manage' => $kode_dosen . '-' . $dosen->nidn,
                'nama_manage' => $this->normalizeName($dosen->nama),
                'sks' => '',
                'kode_prodi' => '',
                'kode_semester' => '',
                'nama_prodi' => $this->normalizeText($dosen->program_studi),
                'nama_matkul' => '',
                'nama_dosen' => '',
                'kapasitas_kelas' => 0,
                'name' => $user_login->name,
                'image' => $user_login->image,
                'created_at' => date("Y-m-d H:i:s")
            ]);

            return redirect('/managekuliah/managedosen')->with('status', 'Hapus data berhasil diajukan ke admin!');
        }

        DB::table('dosen')->where('kode_dosen', $kode_dosen)->delete();

        return redirect('/managekuliah/managedosen')->with('status', 'Data dosen berhasil dihapus!');
    }
}