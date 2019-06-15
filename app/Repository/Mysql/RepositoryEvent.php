<?php
 
namespace App\Repository\Mysql;

use Illuminate\Container\EntryNotFoundException;
use Illuminate\Support\Collection;
use App\Models\Event;
use App\Repository\Interfaces\RepositoryEventInterface;

class RepositoryEvent implements RepositoryEventInterface
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
        $model = Event::find($iId);
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
        return Event::with(['match', 'eventType', 'player'])->get()->toArray();

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
        $model = Event::with(['league', 'matches'])->find($iId);
        if ($model) {
            return collect($model);
        }
        throw new EntryNotFoundException();
    }

    /**
     * GET MODEL BY MATCH ID
     *
     * @param int $iId
     *
     * @return Collection
     * @throws EntryNotFoundException
     */
    public function getByMatch(int $iId): Collection
    {
        $model = Event::where('match_id', $iId)->get();
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
        $model = new Event;
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
        $iId = (int)$params['event_id'];
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
     * DE ACTIVE MODEL BY MATCH ID
     *
     * @param int $iId
     *
     * @return bool
     */
    public function deActiveByMatchId(int $iId): bool
    {
        return Event::where('match_id', $iId)->update(['active' => false]);
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
        return collect($params)->only(['event_id', 'match_id', 'event_type_id', 'player_id', 'minute', 'active'])->toArray();
    }

}