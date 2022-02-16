<?php

namespace App\Managers\Extraction;

use DeGraciaMathieu\Manager\Manager;
use App\Managers\Extraction\Contracts\Driver;

class ExtractionManager extends Manager {

    public function createStreamDriver(): Repository
    {
        $config = config('managers.extraction.drivers.stream');

        $driver = new Drivers\Stream($config);

        return $this->getRepository($driver);
    }

    protected function getRepository(Driver $driver): Repository
    {
        return new Repository($driver);
    }

    public function getDefaultDriver(): string
    {
        return config('managers.extraction.default_driver');
    }
}