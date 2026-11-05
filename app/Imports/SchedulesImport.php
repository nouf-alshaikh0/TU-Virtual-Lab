<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Schedules;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SchedulesImport implements ToModel, WithStartRow
{
    
    public function model(array $row)
    {
        $dateNow = Carbon::now();
        $date = Carbon::create($row[4], $row[3], $row[2], $row[5], $row[6], 0);
        return new Schedules([
            'section_id'    => $row[0],
            'week_id'       => $row[1], 
            'date'          => $date,
            'hrs'           => $row[7],
            'status_id'     => 1,
            'created_at'    => $dateNow,
            'updated_at'    => $dateNow,
        ]);
    }
    public function startRow(): int
    {
     return 2;
    }
}
