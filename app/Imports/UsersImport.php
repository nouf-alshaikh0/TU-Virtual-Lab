<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class UsersImport implements ToModel, WithStartRow

{
    
    public function model(array $row)
    {
        return new User([
            'name'     => $row[0],
           'email'    => $row[1], 
           'email_verified_at' => Carbon::now(),
           'password' => Hash::make($row[2]),
           'permission_id' => $row[3],
           'semester_id' => $row[4],
           'status_id' => 1,
        ]);
    }
    public function startRow(): int
    {
        return 2;
    }
}
