<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Section;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SectionsImport implements ToModel, WithStartRow
{
    public function model(array $row)
    {
        $dateNow = Carbon::now();
        return new Section([
            'name'          => $row[0],
            'course_id'     => $row[1], 
            'instructor_id' => $row[2],
            'created_at'    => $dateNow,
            'updated_at'    => $dateNow,
        ]);
    }
    public function startRow(): int
    {
        return 2;
    }
}
