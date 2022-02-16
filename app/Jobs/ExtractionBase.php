<?php

namespace App\Jobs;

use Illuminate\Support\Str;
use App\Jobs\TransformData as JobsTransformData;
use App\Models\Extraction;
use App\Managers\Extraction\Repository as ManagerRepository;
use Closure;
use PhpParser\Builder\Class_;
use PhpParser\Node\Expr\Cast\String_;

class ExtractionBase
{
    protected $manager;
    protected $uuid;
    protected $extraction;
    protected $lastStatus = 'finished';

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
    }

    private function setRunningStatus(){
       $this->setExtractionStatus($this->runningStatus);
    }

    private function setLastStatus(){
        $this->setExtractionStatus($this->lastStatus);
     }

    private function setExtractionStatus($status){
        $this->extraction->status = $status;
        $this->extraction->save();
    }

}
