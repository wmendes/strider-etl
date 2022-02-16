<?php

namespace App\Imports;

use App\Models\Stream;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Illuminate\Support\Carbon;

class StreamsImport implements ToModel, WithHeadingRow, WithUpserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Stream([
            'movie_title'     => $row['movie_title'],
            'user_email'    => $row['user_email'], 
            'size_mb' => $row['size_mb'],
            'start_at' => Carbon::parse($row['start_at']),
            'end_at' => Carbon::parse($row['end_at'])
        ]);
    }

    public function uniqueBy()
    {
        return ['movie_title','user_email','size_mb','start_at','end_at'];
    }
}
