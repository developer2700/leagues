<?php

namespace App\Repository\Interfaces;

use Illuminate\Support\Collection;

interface RepositoryEventInterface extends RepositoryInterface
{
    /**
     * GET MODEL BY MATCH ID
     *
     * @param int $iId
     *
     * @return Collection
     */
    public function getByMatch(int $iId): Collection;

}
