<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use App\Models\Extraction;
use App\Models\Item;

class CreateItems implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;
    private $collection;
    private $extraction;
    private $classname;

    public function __construct(Collection $collection, Extraction $extraction, String $classname)
    {
        $this->collection = $collection;
        $this->extraction = $extraction;
        $this->classname = $classname;
    }

    public function handle()
    {
        $this->collection->chunk(200)->each(function($entity){
            Item::create(['extraction_id' => $this->extraction->uuid,'entity' => $this->classname, 'data' => json_encode($entity)]);
        });
    }
}
