<?php
 

namespace App\Repository\Mysql;

use Illuminate\Container\EntryNotFoundException;
use Illuminate\Support\Collection;
use App\Models\Contract;
use App\Repository\Interfaces\RepositoryContractInterface;

class RepositoryContract implements RepositoryContractInterface
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
        $model = Contract::find($iId);
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
        return Contract::with(['team', 'player'])->get()->toArray();

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
        $model = Contract::with(['team', 'player'])->find($iId);
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
        $model = new Contract;
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
        $iId = (int)$params['contract_id'];
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
        return collect($params)->only(['contract_id', 'player_id', 'team_id', 'start_at', 'end_at'])->toArray();
    }

}