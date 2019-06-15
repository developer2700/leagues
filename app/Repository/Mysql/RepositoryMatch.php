<?php
 
namespace App\Repository\Mysql;

use Illuminate\Container\EntryNotFoundException;
use Illuminate\Support\Collection;
use App\Models\Match;
use App\Repository\Interfaces\RepositoryMatchInterface;

class RepositoryMatch implements RepositoryMatchInterface
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
        $model = Match::find($iId);
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
        return Match::with(['week', 'hostTeam', 'guestTeam', 'events'])->get()->toArray();

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
        $model = Match::with(['week', 'hostTeam', 'guestTeam', 'events'])->find($iId);
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
        $model = new Match;
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
        $iId = (int)$params['match_id'];
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
     * CHECK ALL WEEK MATCHES IS END
     *
     * @param $iId
     *
     * @return bool
     */
    public function checkWeekIsEnd($iId): bool
    {
        $matchesCount = Match::where('week_id', $iId)->where('start_at', '!=', null)->where('end_at', '=', null)->first();
        if ($matchesCount) {
            return false;
        }
        return true;
    }

    /**
     * MAP MODEL DATAgi
     *
     * @param array $params
     *
     * @return array
     */
    protected function mapData(array $params): array
    {
        return collect($params)->only(['match_id', 'week_id', 'host_team_id', 'guest_team_id', 'host_result', 'guest_result', 'start_at', 'end_at'])->toArray();
    }

}