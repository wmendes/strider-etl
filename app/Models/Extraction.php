<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extraction extends Model
{

    use HasFactory;

    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;  


    protected $fillable = [
        'uuid',
        'datasource',
        'status',
        'extracted_at',
        'transformed_at',
        'loaded_at',
        'cleaned_at',
        'finished_at',
    ];
}
