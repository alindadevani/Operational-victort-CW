<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected array $filters;

    /**
     * @param  array  $filters  Filter yang sama seperti di halaman history (search, tanggal, team_id)
     */
    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Mengambil data absensi sesuai filter yang aktif di halaman history.
     */
    public function collection()
    {
        $query = Attendance::with('team');

        if (!empty($this->filters['search'])) {
            $query->where('nama', 'like', '%'.$this->filters['search'].'%');
        }

        if (!empty($this->filters['tanggal'])) {
            $query->whereDate('tanggal', $this->filters['tanggal']);
        }

        if (!empty($this->filters['team_id'])) {
            $query->where('team_id', $this->filters['team_id']);
        }

        return $query->latest('tanggal')->latest('jam_masuk')->get();
    }

    /**
     * Judul kolom di file Excel.
     */
    public function headings(): array
    {
        return ['No', 'Nama', 'Team', 'Tanggal', 'Jam Masuk', 'Status'];
    }

    /**
     * Mapping tiap baris data ke kolom Excel.
     */
    public function map($attendance): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $attendance->nama,
            $attendance->team->nama_team ?? '-',
            $attendance->tanggal->format('d-m-Y'),
            $attendance->jam_masuk,
            $attendance->status,
        ];
    }

    /**
     * Membuat baris judul (heading) tebal.
     */
    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
