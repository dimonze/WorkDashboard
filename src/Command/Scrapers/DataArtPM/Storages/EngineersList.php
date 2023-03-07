<?php

namespace App\Command\Scrapers\DataArtPM\Storages;

use App\Command\Scrapers\DataArtPM\Entities\Engineer;

class EngineersList
{
    private array $engineer = [];

    public function addEngineer(Engineer $engineer): void
    {
        $this->engineer[$engineer->getId()] = $engineer;
    }

    public function getEngineer(int $id): Engineer|bool
    {
        if(!array_key_exists($id, $this->engineer)) return false;
        return $this->engineer[$id];
    }

    /**
     * Returns a list of Engineer objects
     * @return Engineer[]
     */
    public function getAllEngineers()
    {
        return $this->engineer;
    }
}