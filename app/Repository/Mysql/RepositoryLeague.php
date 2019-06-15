<?php
 

namespace App\Repository\Mysql;

use Illuminate\Container\EntryNotFoundException;
use Illuminate\Support\Collection;
use App\Models\League;
use App\Repository\Interfaces\RepositoryLeagueInterface;

class RepositoryLeague implements RepositoryLeagueInterface
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
        $model = League::find($iId);
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
        return League::with(['weeks', 'leagueTeam', 'teams'])->get()->toArray();

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
        $model = League::with(['weeks', 'leagueTeam', 'teams'])->find($iId);
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
        $model = new League;
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
        $iId = (int)$params['league_id'];
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
        return collect($params)->only(['name', 'nowWeek'])->toArray();
    }

}