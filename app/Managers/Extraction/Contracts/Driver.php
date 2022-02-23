<?php

namespace App\Managers\Extraction\Contracts;
use App\Models\Extraction;

interface Driver {
    public function extractData(Extraction $extraction): void;
    public function transformData(Extraction $extraction): void;
    public function loadData(Extraction $extraction);
    public function clearData(Extraction $extraction): void;
}