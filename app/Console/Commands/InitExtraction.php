<?php

namespace App\Console\Commands;

use App\Jobs\InitExtraction as JobsInitExtraction;
use Illuminate\Console\Command;
use App\Managers\Extraction\ExtractionManager;

class InitExtraction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'etl:initextraction {datasource}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $manager = app(ExtractionManager::class)->driver($this->argument('datasource'));
        JobsInitExtraction::dispatch($manager);
    }
}
