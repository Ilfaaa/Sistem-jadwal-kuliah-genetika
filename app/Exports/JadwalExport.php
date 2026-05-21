<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class JadwalExport implements FromCollection, WithHeadings, WithEvents
{
    protected string $semester;
    protected string $tahun;

    public function __construct(string $semester, string $tahun)
    {
        $this->semester = $semester;

        // contoh input "2024-2025" -> simpan jadi "2024/2025"
        $tahunTemp = explode('-', $tahun);
        $this->tahun = implode('/', $tahunTemp);
    }

    public function collection()
    {
        return DB::table('jadwal')
            ->where('semester', $this->semester)
            ->where('tahun_ajaran', $this->tahun)
            ->get();
    }

    public function headings(): array
    {
        return [
            ['Fakultas Teknik Universitas Diponegoro'], // Nama Kampus
            ['Jl. Prof. Soedarto, Tembalang, Kec. Tembalang, Kota Semarang, Jawa Tengah 50275'], // Alamat
            ['Jadwal Kuliah Semester ' . $this->semester . ' - Tahun ' . $this->tahun], // Info Semester dan Tahun
            [], // pemisah
            ['NO', 'Mata Kuliah', 'Dosen Pengajar', 'Kelas', 'Jumlah SKS', 'Ruangan', 'Hari', 'Jam Masuk', 'Jam Keluar', 'Semester', 'Tahun Ajaran'],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                /**
                 * =========================================================
                 * KONFIG LOGO (UBAH DI SINI SAJA)
                 * =========================================================
                 */
                $leftLogoPath  = public_path('img/logo-sijatom.png'); // logo kiri
                $rightLogoPath = null; // contoh: public_path('img/logo-ft.png'); // logo kanan (opsional)

                /**
                 * =========================================================
                 * PASANG LOGO (AMAN: hanya jika file ada)
                 * =========================================================
                 */
                if ($leftLogoPath && file_exists($leftLogoPath)) {
                    $logoLeft = new Drawing();
                    $logoLeft->setName('Logo Kiri');
                    $logoLeft->setDescription('Logo Kiri');
                    $logoLeft->setPath($leftLogoPath);
                    $logoLeft->setHeight(60);
                    $logoLeft->setCoordinates('A1');
                    $logoLeft->setWorksheet($sheet);
                }

                if ($rightLogoPath && file_exists($rightLogoPath)) {
                    $logoRight = new Drawing();
                    $logoRight->setName('Logo Kanan');
                    $logoRight->setDescription('Logo Kanan');
                    $logoRight->setPath($rightLogoPath);
                    $logoRight->setHeight(60);
                    $logoRight->setCoordinates('K1');
                    $logoRight->setWorksheet($sheet);
                }

                /**
                 * =========================================================
                 * HEADER (MERGE & STYLE)
                 * Catatan:
                 * - Kolom tabel adalah A sampai K (11 kolom)
                 * - Kalau ada logo kanan di K1, teks header sebaiknya merge sampai J
                 * =========================================================
                 */
                $sheet->mergeCells('A1:J1'); // sisakan K untuk logo kanan
                $sheet->mergeCells('A2:J2');
                $sheet->mergeCells('A3:J3');

                $sheet->getStyle('A1:K3')->getFont()->setBold(true)->setSize(10);

                // Pusatkan hanya area teks (A..J), biar tidak "mengganggu" area logo kanan (K)
                $sheet->getStyle('A1:J3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                /**
                 * =========================================================
                 * STYLE HEADER TABEL
                 * =========================================================
                 */
                $sheet->getStyle('A5:K5')->getFont()->setBold(true);
                $sheet->getStyle('A5:K5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                /**
                 * =========================================================
                 * BORDER TABEL
                 * =========================================================
                 */
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle('A5:K' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                /**
                 * =========================================================
                 * TANDA TANGAN + TANGGAL (INDONESIA)
                 * =========================================================
                 */
                $signatureRow = $lastRow + 2;

                $months = [
                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                ];

                $now = now();
                $monthIndo = $months[$now->format('m')] ?? $now->format('m');
                $formattedDate = $now->format('d') . ' ' . $monthIndo . ' ' . $now->format('Y');

                // Lokasi & tanggal
                $sheet->setCellValue('I' . $signatureRow, 'Semarang, ' . $formattedDate);
                $sheet->mergeCells('I' . $signatureRow . ':K' . $signatureRow);

                // Tanda tangan
                $sheet->setCellValue('I' . ($signatureRow + 4), '_________________');
                $sheet->mergeCells('I' . ($signatureRow + 4) . ':K' . ($signatureRow + 4));

                $sheet->setCellValue('I' . ($signatureRow + 5), 'Prof. Dr. Ir. Jamari, S.T., M.T., IPU., ASEAN Eng.');
                $sheet->mergeCells('I' . ($signatureRow + 5) . ':K' . ($signatureRow + 5));

                // Style tanda tangan
                $sheet->getStyle('I' . $signatureRow . ':K' . ($signatureRow + 5))->getFont()->setBold(true);
                $sheet->getStyle('I' . ($signatureRow + 4) . ':K' . ($signatureRow + 5))
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
