<?php
  
namespace App\Imports;
  
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
  
class UsersImport implements ToModel, WithHeadingRow, WithUpserts
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'first_name'     => $row['first_name'],
            'last_name'    => $row['last_name'], 
            'email' => $row['email']
        ]);
    }

    public function uniqueBy()
    {
        return 'email';
    }
}
