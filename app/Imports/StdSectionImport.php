<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Std_Section;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class StdSectionImport implements ToModel, WithStartRow
{
    public function model(array $row)
    {
        $dateNow = Carbon::now();
        return new Std_Section([
            'std_id'        => $row[0],
            'semester_id'   => $row[1], 
            'section_id'    => $row[2],
            'created_at'    => $dateNow,
            'updated_at'    => $dateNow,
        ]);
    }
    public function startRow(): int
    {
        return 2;
    }
}
