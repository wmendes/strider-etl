<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Extraction;
use App\Managers\Extraction\Repository as ManagerRepository;
use App\Jobs\ExtractionBase;

class ClearData extends ExtractionBase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $runningStatus = 'cleaning';
    protected $finishedStatus = 'cleaned';

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
        $this->manager->clearData($this->extraction);
        $this->dispatchNext(null, [$this->manager, $this->extraction]);
    }
}
