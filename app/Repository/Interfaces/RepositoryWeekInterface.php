<?php
 

namespace App\Repository\Interfaces;

use Illuminate\Support\Collection;

interface RepositoryWeekInterface extends RepositoryInterface
{
    /**
     * GET MODEL BY NAME
     *
     * @param string $sName
     *
     * @return Collection
     */
    public function getByName(string $sName): Collection;
}
