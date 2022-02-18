<?php

namespace App\Jobs;

use Illuminate\Support\Str;
use App\Jobs\TransformData as JobsTransformData;
use App\Models\Extraction;
use App\Managers\Extraction\Repository as ManagerRepository;
use Carbon\Carbon;
use Closure;
use PhpParser\Builder\Class_;
use PhpParser\Node\Expr\Cast\String_;

class ExtractionBase
{
    protected $manager;
    protected $uuid;
    protected $extraction;
    protected $lastStatus = 'finished';
    private $timestampColumnNames = [
        'extracted' => 'extracted_at',
        'loaded' => 'loaded_at',
        'transformed' => 'transformed_at',
        'cleaned' => 'cleaned_at',
        'finished' => 'finished_at',
    ];

    public function __construct(ManagerRepository $manager, ?Extraction $extraction)
    {
        $this->manager = $manager;
        $this->uuid = Str::uuid();
        if(!$extraction){
            $extraction = Extraction::create([
                'uuid' => $this->uuid,
                'datasource' => $this->manager->driverSlug
            ]);
        }

        $this->extraction = $extraction;
        
        $this->setRunningStatus();

    }

    public function dispatchNext(?String $next, Array $parameters){
        $this->setFinishedStatus();
        
        if($next)
            $next::dispatch(...$parameters);
        else
            $this->setLastStatus();
    }

    private function setFinishedStatus(){
        $this->setExtractionStatus($this->finishedStatus);
        $this->setExtractionTimestampsForStatus($this->finishedStatus);
    }

    private function setRunningStatus(){
       $this->setExtractionStatus($this->runningStatus);
    }

    private function setLastStatus(){
        $this->setExtractionStatus($this->lastStatus);
        $this->setExtractionTimestampsForStatus($this->lastStatus);
     }

    private function setExtractionStatus($status){
        $this->extraction->status = $status;
        $this->extraction->save();
    }

    private function setExtractionTimestampsForStatus($status){
        if(array_key_exists($status,$this->timestampColumnNames)){
            $this->extraction->{$this->timestampColumnNames[$status]} = Carbon::now();
            $this->extraction->save();
        }
    }

}
