<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\ExtractData;
use App\Jobs\ExtractionBase;
use App\Managers\Extraction\Repository as ManagerRepository;

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
        $this->dispatchNext(ExtractData::class, [$this->manager, $this->extraction]);
    }
}
