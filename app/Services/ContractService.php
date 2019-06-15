<?php
 
namespace App\Services;


use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use App\Repository\Mysql\RepositoryContract;
use \Illuminate\Support\Facades\Validator;

class ContractService extends BaseService
{

    private $Repository;

    public function __construct(RepositoryContract $repository = null)
    {
        $this->Repository = $repository ?? new RepositoryContract();


    }

    /**
     * GET ALL MODEL DATA
     *
     * @return array
     */
    public function getAll(): array
    {
        return $this->Repository->getAll();
    }

    /**
     * GET MODEL DATA BY ID
     *
     * @param int $iId
     *
     * @return Collection
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function get(int $iId): Collection
    {
        return $this->Repository->get($iId);
    }

    /**
     * CREATE NEW MODEL
     *
     * @param array $params
     *
     * @return Collection
     * @throws ValidationException
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function set(array $params): Collection
    {
        $params = $this->validate($params);
        return $this->Repository->set($params);
    }

    /**
     * UPDATE MODEL BY ID
     *
     * @param array $params
     * @param int $iId
     *
     * @return Collection
     * @throws ValidationException
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function update(array $params, int $iId): Collection
    {
        $params = $this->validate($params);
        $params['contract_id'] = $iId;
        return $this->Repository->update($params);
    }

    /**
     * DELETE MODEL
     *
     * @param int $iId
     *
     * @return bool
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function delete(int $iId): bool
    {
        return $this->Repository->delete($iId);
    }

    /**
     * VALIDATE MODEL ATTRIBUTES
     *
     * @param array $params
     *
     * @return array
     * @throws ValidationException
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    private function validate(array $params): array
    {


        if (array_key_exists('player_id', $params)) {
            $playerService = new playerService();
            $playerService->get($params['player_id']);
        }

        if (array_key_exists('team_id', $params)) {
            $TeamService = new TeamService();
            $TeamService->get($params['team_id']);
        }


        $rules = [
            'player_id' => 'required',
            'team_id' => 'required',
            'start_at' => 'date|nullable',
            'end_at' => 'date|nullable'
        ];

        $validator = Validator::make($params, $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        } else {
            return $params;
        }
    }
}