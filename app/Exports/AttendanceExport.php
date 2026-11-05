<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceExport implements FromCollection, WithHeadings
{
    protected $data;

    // Constructor to receive the data
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    
    public function collection()
    {
        $attendanceData = collect();

        foreach ($this->data['students'] as $student) {
            $row = [];
            $row[] = $student['student']['name'];

            foreach ($this->data['schedules'] as $schedule) {
                $attendance = collect($schedule['attendances'])->firstWhere('std_id', $student['student']['id']);
                $row[] = $attendance ? 'Present' : 'Absent';
            }

            $attendanceData->push($row);
        }

        return $attendanceData;
    }

    /**
     * Define the headings for the Excel file
     */
    public function headings(): array
    {
        $headings = ['Student Name'];

        foreach ($this->data['schedules'] as $schedule) {
            $sessionBase = 'Session';
            $weekName = $schedule['week']['name'];
            $i = 1;
            
            do {
                $session = $sessionBase . ' ' . $i . ' | ' . $weekName;
                $i++;
            } while (in_array($session, $headings));
        
            $headings[] = $session;
        }

        return $headings;
    }
}

