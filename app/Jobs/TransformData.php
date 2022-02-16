<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Extraction;
use \App\Jobs\ClearData as JobsClearData;
use App\Managers\Extraction\Repository as ManagerRepository;

class TransformData extends ExtractionBase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $runningStatus = 'transforming';
    protected $finishedStatus = 'transformed';

    public function __construct(ManagerRepository $manager, Extraction $extraction)
    {
        parent::__construct($manager, $extraction);
    }

    public function handle()
    {
        $this->manager->transformData($this->extraction);
        $this->dispatchNext(JobsClearData::class, [$this->manager, $this->extraction]);
    }
}
