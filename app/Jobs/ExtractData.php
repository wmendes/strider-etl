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
use \App\Jobs\LoadData as JobsLoad;
use App\Models\Extraction;
use App\Jobs\ExtractionBase;
use App\Managers\Extraction\Repository as ManagerRepository;

class ExtractData extends ExtractionBase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $runningStatus = 'extracting';
    protected $finishedStatus = 'extracted';

    public function __construct(ManagerRepository $manager, Extraction $extraction)
    {
        parent::__construct($manager, $extraction);
    }

    public function handle()
    {
        $this->manager->extractData($this->extraction);
        $this->dispatchNext(JobsLoad::class, [$this->manager, $this->extraction]);
    }
}
