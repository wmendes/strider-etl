<?php

namespace App\Managers\Extraction\Drivers;

use App\Managers\Extraction\Contracts\Driver;
use Illuminate\Support\Facades\Storage;
use App\Models\Extraction;
use League\Flysystem\MountManager;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Models\Item;
use App\Models\User;
use App\Models\Movie;
use App\Models\Stream as StreamModel;
use App\Models\Author;
use App\Models\Book;
use App\Models\Review;
use App\Jobs\CreateItems;
use Illuminate\Support\Facades\Bus;
use Illuminate\Bus\Batch;
use Illuminate\Bus\PendingBatch;
use Mockery\Matcher\Any;

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

    public function loadData(Extraction $extraction): PendingBatch {
        $userFilePath = Storage::disk('staging-s3')->path("{$this->slug}/{$extraction->uuid}/users.csv");
        $moviesFilePath = Storage::disk('staging-s3')->path("{$this->slug}/{$extraction->uuid}/movies.csv");        
        $streamsFilePath = Storage::disk('staging-s3')->path("{$this->slug}/{$extraction->uuid}/streams.csv");
        
        $batchJobs = [];

        $batchJobs[] = new CreateItems((new FastExcel)->import($userFilePath), $extraction, User::class);
        $batchJobs[] = new CreateItems((new FastExcel)->import($moviesFilePath), $extraction, Movie::class);
        $batchJobs[] = new CreateItems((new FastExcel)->import($streamsFilePath), $extraction, StreamModel::class);

        $batchJobs[] = new CreateItems(collect(json_decode(Storage::disk('staging-s3')->get("{$this->slug}/{$extraction->uuid}/authors.json"))), $extraction, Author::class);
        $batchJobs[] = new CreateItems(collect(json_decode(Storage::disk('staging-s3')->get("{$this->slug}/{$extraction->uuid}/books.json"))), $extraction, Book::class);
        $batchJobs[] = new CreateItems(collect(json_decode(Storage::disk('staging-s3')->get("{$this->slug}/{$extraction->uuid}/reviews.json"))), $extraction, Review::class);


        return Bus::batch($batchJobs);

        // (new FastExcel)->import($userFilePath)->chunk(200)->each(function($user) use ($extraction){
        //     Item::create(['extraction_id' => $extraction->uuid,'entity' => User::class, 'data' => json_encode($user)]);
        // });

        // (new FastExcel)->import($moviesFilePath)->chunk(200)->each(function($movie) use ($extraction){
        //     Item::create(['extraction_id' => $extraction->uuid,'entity' => Movie::class, 'data' => json_encode($movie)]);
        // });

        // (new FastExcel)->import($streamsFilePath)->chunk(200)->each(function($stream) use ($extraction){
        //     Item::create(['extraction_id' => $extraction->uuid,'entity' => StreamModel::class, 'data' => json_encode($stream)]);
        // });        

        // collect(json_decode(Storage::disk('staging-s3')->get("{$this->slug}/{$extraction->uuid}/authors.json")))->chunk(200)->each(function($author) use ($extraction){
        //     Item::create(['extraction_id' => $extraction->uuid,'entity' => Author::class, 'data' => json_encode($author)]);
        // });

        // collect(json_decode(Storage::disk('staging-s3')->get("{$this->slug}/{$extraction->uuid}/books.json")))->chunk(200)->each(function($book) use ($extraction){
        //     Item::create(['extraction_id' => $extraction->uuid,'entity' => Book::class, 'data' => json_encode($book)]);
        // }); 
        
        // collect(json_decode(Storage::disk('staging-s3')->get("{$this->slug}/{$extraction->uuid}/reviews.json")))->chunk(200)->each(function($review) use ($extraction){
        //     Item::create(['extraction_id' => $extraction->uuid,'entity' => Review::class, 'data' => json_encode($review)]);
        // });        

        // $batch = Bus::batch([]);

        // $userCollection
        // ->cursor()
        // ->map(fn ($user) => $this->createImportUserJob($user))
        // ->chunk(100)
        // ->each(function(LazyCollection $users) use ($batch){
        //     $batch->add($users);
        // });

        // Excel::import(new UsersImport,"{$this->slug}/{$extraction->uuid}/users.csv", 'staging-s3');        
        // Excel::import(new MoviesImport,"{$this->slug}/{$extraction->uuid}/movies.csv", 'staging-s3');
        // Excel::import(new StreamsImport,"{$this->slug}/{$extraction->uuid}/streams.csv", 'staging-s3');
        
        
        // $authorsArray = New Collection(json_decode($authors, true));
        
        // $authorsArray = $authorsArray->map(function($item, $key) {
        //     $item['died_at'] = Carbon::parse($item['died_at']);
        //     $item['birth_date'] = Carbon::parse($item['birth_date']);
        //     return $item;
        //  });

        // Author::upsert($authorsArray->toArray(), ['name'], ['birth_date', 'died_at', 'nationality']);


        // $books = Storage::disk('staging-s3')->get("{$this->slug}/{$extraction->uuid}/books.json");
        // $booksArray = json_decode($books, true);

        // Book::upsert($booksArray, ['name'], ['pages', 'author', 'publisher']);


        // $reviews = Storage::disk('staging-s3')->get("{$this->slug}/{$extraction->uuid}/reviews.json");
        // $reviewsArray = json_decode($reviews, true);

        // Review::upsert($reviewsArray, ['movie', 'book'], ['rate', 'resume']);
        
        
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

        Item::where('extraction_id', $extraction->uuid)->delete();
        
    }
}
