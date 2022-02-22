<?php

namespace App\Managers\Extraction\Drivers;

use App\Imports\MoviesImport;
use App\Imports\StreamsImport;
use App\Managers\Extraction\Contracts\Driver;
use Illuminate\Support\Facades\Storage;
use App\Models\Extraction;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\User;
use App\Models\Author;
use App\Models\Book;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use League\Flysystem\MountManager;


class Stream implements Driver {

    protected $config;
    public $slug = 'stream';

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function extractData(Extraction $extraction): void {
        Storage::disk('staging-s3')->makeDirectory("{$this->slug}/{$extraction->uuid}");
        foreach($this->config['files'] as $file){
            $fileData = Storage::disk('ftp')->get($file);
            Storage::disk('staging-s3')->put("{$this->slug}/{$extraction->uuid}/{$file}", $fileData);
        }
    }

    public function transformData(Extraction $extraction): void {

    }

    public function loadData(Extraction $extraction): void {
        Excel::import(new UsersImport,"{$this->slug}/{$extraction->uuid}/users.csv", 'staging-s3');
        Excel::import(new MoviesImport,"{$this->slug}/{$extraction->uuid}/movies.csv", 'staging-s3');
        Excel::import(new StreamsImport,"{$this->slug}/{$extraction->uuid}/streams.csv", 'staging-s3');
        
        $authors = Storage::disk('staging-s3')->get("{$this->slug}/{$extraction->uuid}/authors.json");
        $authorsArray = New Collection(json_decode($authors, true));
        
        $authorsArray = $authorsArray->map(function($item, $key) {
            $item['died_at'] = Carbon::parse($item['died_at']);
            $item['birth_date'] = Carbon::parse($item['birth_date']);
            return $item;
         });

        Author::upsert($authorsArray->toArray(), ['name'], ['birth_date', 'died_at', 'nationality']);


        $books = Storage::disk('staging-s3')->get("{$this->slug}/{$extraction->uuid}/books.json");
        $booksArray = json_decode($books, true);

        Book::upsert($booksArray, ['name'], ['pages', 'author', 'publisher']);


        $reviews = Storage::disk('staging-s3')->get("{$this->slug}/{$extraction->uuid}/reviews.json");
        $reviewsArray = json_decode($reviews, true);

        Review::upsert($reviewsArray, ['movie', 'book'], ['rate', 'resume']);
        
        
    }

    public function clearData(Extraction $extraction): void {

        $mountManager = new MountManager([
            'staging-s3' => Storage::disk('staging-s3')->getDriver(),
            'glacier' => Storage::disk('glacier')->getDriver(),
        ]);

        $files = Storage::disk('staging-s3')->files("{$this->slug}/{$extraction->uuid}");
        
        foreach ($files as $file) {
            $mountManager->move("staging-s3://{$file}", "glacier://{$file}");
        }

        Storage::disk('staging-s3')->deleteDirectory("{$this->slug}/{$extraction->uuid}");
        
    }
}
