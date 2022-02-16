<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Extraction;
use \App\Jobs\TransformData as JobsTransformData;
use App\Managers\Extraction\Repository as ManagerRepository;
use App\Jobs\ExtractionBase;

class LoadData extends ExtractionBase implements ShouldQueue
{
    protected $runningStatus = 'loading';
    protected $finishedStatus = 'loaded';

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(ManagerRepository $manager, Extraction $extraction)
    {
        parent::__construct($manager, $extraction);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->manager->loadData($this->extraction);
        $this->dispatchNext(JobsTransformData::class, [$this->manager, $this->extraction]);
    }
}
