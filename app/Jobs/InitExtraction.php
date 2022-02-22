<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\ExtractData;
use App\Jobs\LoadData;
use App\Jobs\TransformData;
use App\Jobs\ClearData;
use App\Jobs\ExtractionBase;
use App\Managers\Extraction\Repository as ManagerRepository;
use Illuminate\Support\Facades\Bus;

class InitExtraction extends ExtractionBase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $runningStatus = 'init';
    protected $finishedStatus = 'init';

    public function __construct(ManagerRepository $manager)
    {
        parent::__construct($manager, null);
    }

    public function handle()
    {

        Bus::chain([
            new ExtractData,
            new LoadData,
            new TransformData,
            new ClearData
        ])->dispatch([$this->manager, $this->extraction]);

        // $this->dispatchNext(JobsExtractData::class, [$this->manager, $this->extraction]);
    }
}
