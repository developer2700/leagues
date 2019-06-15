<?php

namespace App\Repository\Mysql;

use Illuminate\Container\EntryNotFoundException;
use Illuminate\Support\Collection;
use App\Models\LeagueTeam;
use App\Repository\Interfaces\RepositoryLeagueTeamInterface;

class RepositoryLeagueTeam implements RepositoryLeagueTeamInterface
{

    public function __construct()
    {
    }

    /**
     * RETURN MODEL
     *
     * @param $iId
     *
     * @return mixed
     * @throws EntryNotFoundException
     */
    public function getModel(int $iId)
    {
        $model = LeagueTeam::find($iId);
        if ($model) {
            return $model;
        }
        throw new EntryNotFoundException();
    }

    /**
     * RETURN MODEL BY LEAGUE_ID
     *
     * @param $iId
     *
     * @return mixed
     * @throws EntryNotFoundException
     */
    public function getModelByLeagueId(int $iId)
    {
        $model = LeagueTeam::where('league_id', $iId)->get();
        if ($model) {
            return $model;
        }
        throw new EntryNotFoundException();
    }

    /**
     * GET ALL MODEL
     *
     * @return array
     */
    public function getAll(): array
    {
        return LeagueTeam::with(['league', 'team'])->orderBy('Points', 'DESC')->get()->toArray();

    }

    /**
     *  GET MODEL DATA BY LEAGUE ID
     *
     * @param int $iId
     *
     * @return Collection
     * @throws EntryNotFoundException
     */
    public function getByLeagueId(int $iId): Collection
    {
        $model = LeagueTeam::with(['league', 'team'])->where('league_id', $iId)->orderBy('Points', 'DESC')->get();
        if ($model) {
            return collect($model);
        }
        throw new EntryNotFoundException();
    }

    /**
     * GET MODEL BY ID
     *
     * @param int $iId
     *
     * @return Collection
     * @throws EntryNotFoundException
     */
    public function get(int $iId): Collection
    {
        $model = LeagueTeam::with(['league', 'team'])->find($iId);
        if ($model) {
            return collect($model);
        }
        throw new EntryNotFoundException();
    }

    /**
     * CREATE NEW MODEL
     *
     * @param array $params
     *
     * @return Collection
     */
    public function set(array $params): Collection
    {
        $model = new LeagueTeam;
        foreach ($params as $key => $param) {
            $model->$key = $param;
        }
        $model->save();
        return collect($model);
    }

    /**
     * UPDATE MODEL
     *
     * @param array $params
     *
     * @return Collection
     * @throws EntryNotFoundException
     */
    public function update(array $params): collection
    {
        $iId = (int)$params['league_team_id'];
        $model = $this->getModel($iId);
        foreach ($params as $key => $param) {
            $model->$key = $param;
        }
        $model->save();
        return collect($model);

    }

    /**
     * DELETE MODEL
     *
     * @param int $iId
     *
     * @return bool
     * @throws EntryNotFoundException
     */
    public function delete(int $iId): bool
    {
        $model = $this->getModel($iId);
        return $model->delete();
    }

    /**
     * MAP MODEL DATA
     *
     * @param array $params
     *
     * @return array
     */
    protected function mapData(array $params): array
    {
        return collect($params)->only(['league_team_id', 'league_id', 'team_id', 'played', 'won', 'drawn', 'lost', 'gf', 'ga', 'gd', 'Points', 'percent'])->toArray();
    }

}