<?php
 
namespace App\Repository\Interfaces;

use Illuminate\Support\Collection;

interface RepositoryLeagueTeamInterface extends RepositoryInterface
{
    /**
     * RETURN MODEL BY LEAGUE_ID
     *
     * @param $iId
     *
     * @return mixed
     */
    public function getModelByLeagueId(int $iId);

    /**
     *  GET MODEL DATA BY LEAGUE ID
     *
     * @param int $iId
     *
     * @return Collection
     */
    public function getByLeagueId(int $iId): Collection;
}
