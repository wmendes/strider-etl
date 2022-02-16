<?php

namespace App\Managers\Extraction;

use App\Managers\Extraction\Contracts\Driver;
use App\Models\Extraction;


class Repository {

    protected $driver;
    public $driverSlug;

    public function __construct(Driver $driver)
    {
        $this->driver = $driver;
        $this->driverSlug = $driver->slug;
    }

    public function extractData(Extraction $extraction) {
        return $this->driver->extractData($extraction);

    }
    public function transformData(Extraction $extraction) {
        return $this->driver->transformData($extraction);

    }
    public function loadData(Extraction $extraction) {
        return $this->driver->loadData($extraction);

    }
    public function clearData(Extraction $extraction) {
        return $this->driver->clearData($extraction);

    }    
}