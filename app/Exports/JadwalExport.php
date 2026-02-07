<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class JadwalExport implements FromCollection, WithHeadings, WithEvents
{
    protected $semester;
    protected $tahun;

    public function __construct(string $semester, string $tahun) 
    {
        $this->semester = $semester;
        $tahunTemp = explode('-', $tahun);
        $this->tahun = implode('/', $tahunTemp);
    }

    public function collection()
    {
        return DB::table('jadwal')->where('semester', $this->semester)->where('tahun_ajaran', $this->tahun)->get();
    }

    public function headings(): array
    {
        return [
            ['Fakultas Ilmu Komputer Universitas Kuningan'], // Nama Kampus
            ['Jl. Pramuka No.67, Purwawinangun, Kec. Kuningan, Kabupaten Kuningan, Jawa Barat 45512'], // Alamat Kampus
            ['Jadwal Kuliah Semester ' . $this->semester . ' - Tahun ' . $this->tahun], // Info Semester dan Tahun
            [], // Baris kosong untuk pemisah
            ['NO', 'Mata Kuliah', 'Dosen Pengajar', 'Kelas', 'Jumlah SKS', 'Ruangan', 'Hari', 'Jam Masuk', 'Jam Keluar', 'Semester', 'Tahun Ajaran'], // Header Tabel
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Tambahkan Logo di sebelah kiri
                $drawing1 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing1->setName('Logo Universitas');
                $drawing1->setDescription('Logo Universitas');
                $drawing1->setPath(public_path('img/logo-unsam.png')); // Path ke logo pertama
                $drawing1->setHeight(60);
                $drawing1->setCoordinates('A1');
                $drawing1->setWorksheet($sheet);

                // // Tambahkan Logo di sebelah kanan
                // $drawing2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                // $drawing2->setName('Logo Fakultas');
                // $drawing2->setDescription('Logo Fakultas');
                // $drawing2->setPath(public_path('img/logo-fkom.png')); // Path ke logo kedua
                // $drawing2->setHeight(60);
                // $drawing2->setCoordinates('K1');
                // $drawing2->setWorksheet($sheet);

                // Style Header
                $sheet->mergeCells('A1:J1'); // Merge untuk nama kampus (tinggalkan kolom K untuk logo kedua)
                $sheet->mergeCells('A2:K2'); // Merge untuk alamat kampus
                $sheet->mergeCells('A3:K3'); // Merge untuk info semester dan tahun
                $sheet->getStyle('A1:K3')->getFont()->setBold(true)->setSize(10);
                $sheet->getStyle('A1:K3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Pusatkan teks header

                // Style untuk header tabel
                $sheet->getStyle('A5:K5')->getFont()->setBold(true); 
                $sheet->getStyle('A5:K5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Tambahkan border untuk tabel
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle('A5:K' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                        ],
                    ],
                ]);

                // Tambahkan tanda tangan di bawah tabel
                $signatureRow = $lastRow + 2;

                // Ubah format tanggal dengan bulan dalam bahasa Indonesia
                $months = [
                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                ];

                $now = now();
                $monthIndo = $months[$now->format('m')];
                $formattedDate = $now->format('d') . ' ' . $monthIndo . ' ' . $now->format('Y');

                $sheet->setCellValue('I' . $signatureRow, 'Kuningan, ' . $formattedDate); // Waktu dan tempat
                $sheet->mergeCells('I' . $signatureRow . ':K' . $signatureRow);

                // Penandatangan
                $sheet->setCellValue('I' . ($signatureRow + 4), '_________________');
                $sheet->mergeCells('I' . ($signatureRow + 4) . ':K' . ($signatureRow + 4));
                $sheet->setCellValue('I' . ($signatureRow + 5), 'Tito Sugiharto, S.Kom., M.Eng.');
                $sheet->mergeCells('I' . ($signatureRow + 5) . ':K' . ($signatureRow + 5));

                // Style untuk tanda tangan
                $sheet->getStyle('I' . $signatureRow . ':K' . ($signatureRow + 5))->getFont()->setBold(true);
                $sheet->getStyle('I' . ($signatureRow + 4) . ':K' . ($signatureRow + 5))
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // Pusatkan tanda tangan
            },
        ];
    }

}

