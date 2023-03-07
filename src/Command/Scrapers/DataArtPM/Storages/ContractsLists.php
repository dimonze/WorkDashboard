<?php

namespace App\Command\Scrapers\DataArtPM\Storages;

use App\Command\Scrapers\DataArtPM\Entities\Contract;

class ContractsLists
{
    private array $contract = [];

    public function addContract(Contract $contract): void
    {
        $this->contract[$contract->getId()] = $contract;
    }

    public function getContract(int $id): Contract
    {
        return $this->contract[$id];
    }

    /**
     * Returns a list of Contract objects
     * @return Contract[]
     */
    public function getAllContracts()
    {
        return $this->contract;
    }
}