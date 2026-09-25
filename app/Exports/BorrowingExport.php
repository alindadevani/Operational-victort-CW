<?php

namespace App\Exports;

use App\Models\Borrowing;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BorrowingExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Borrowing::with(['team', 'car']);

        if (!empty($this->filters['team_id'])) {
            $query->where('team_id', $this->filters['team_id']);
        }

        if (!empty($this->filters['car_id'])) {
            $query->where('car_id', $this->filters['car_id']);
        }

        if (!empty($this->filters['tanggal'])) {
            $query->whereDate('tanggal', $this->filters['tanggal']);
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        return $query->latest('tanggal')->get();
    }

    public function headings(): array
    {
        return [
            'No', 'Nama', 'Team', 'Mobil', 'Plat Nomor', 'Lokasi',
            'Keperluan', 'Tanggal', 'Jam Keluar', 'Jam Kembali', 'Status',
        ];
    }

    public function map($borrowing): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $borrowing->nama,
            $borrowing->team->nama_team ?? '-',
            $borrowing->car->nama_mobil ?? '-',
            $borrowing->car->plat_nomor ?? '-',
            $borrowing->lokasi,
            $borrowing->keperluan,
            $borrowing->tanggal->format('d-m-Y'),
            $borrowing->jam_keluar,
            $borrowing->jam_kembali ?? '-',
            $borrowing->status,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
