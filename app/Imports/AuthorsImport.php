<?php
  
namespace App\Imports;
  
use App\Models\Author;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithUpserts;
  
class AuthorssImport implements ToModel, WithUpserts
{

    public function model(array $row)
    {
        return new Author([
            'name'     => $row['name'],
            'birth_date'    => $row['birth_date'], 
            'died_at' => $row['died_at'],
            'nationality' => $row['nationality']
        ]);
    }

    public function uniqueBy()
    {
        return 'name';
    }
}
