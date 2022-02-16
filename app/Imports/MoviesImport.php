<?php

namespace App\Imports;

use App\Models\Movie;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class MoviesImport implements ToModel, WithHeadingRow, WithUpserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Movie([
            'title'     => $row['title'],
            'duration_mins'    => $row['duration_mins'], 
            'original_language' => $row['original_language'],
            'size_mb' => $row['size_mb']
        ]);
    }

    public function uniqueBy()
    {
        return 'title';
    }    
}
