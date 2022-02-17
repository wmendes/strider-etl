<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Jobs\ExtractData as JobsExtractData;
use App\Models\Extraction;
use App\Jobs\ExtractionBase;
use App\Managers\Extraction\Repository as ManagerRepository;
use Illuminate\Console\Command;

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
        $this->dispatchNext(JobsExtractData::class, [$this->manager, $this->extraction]);
    }
}
