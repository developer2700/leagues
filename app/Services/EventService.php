<?php
 

namespace App\Services;


use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use App\Repository\Mysql\RepositoryEvent;
use \Illuminate\Support\Facades\Validator;

class EventService extends BaseService
{

    private $Repository;

    public function __construct(RepositoryEvent $repository = null)
    {
        $this->Repository = $repository ?? new RepositoryEvent();
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
     * GET MODELS DATA BU MATCH ID
     *
     * @param int $iId
     *
     * @return Collection
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function getByMatch(int $iId)
    {
        return $this->Repository->getByMatch($iId);
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
        $params['event_id'] = $iId;
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
     * DE ACTIVE MODEL BY MATCH ID
     *
     * @param int $iId
     *
     * @return bool
     */
    public function deActiveByMatchId(int $iId): bool
    {
        return $this->Repository->deActiveByMatchId($iId);
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

        if (array_key_exists('match_id', $params)) {
            $MatchService = new MatchService();
            $MatchService->get($params['match_id']);
        }

        if (array_key_exists('event_type_id', $params)) {
            $SettingService = new SettingService();
            $SettingService->get($params['event_type_id']);
        }

        if (array_key_exists('player_id', $params) && $params['player_id']) {
            $PlayerService = new PlayerService();
            $PlayerService->get($params['player_id']);
        }

        $rules = [
            'match_id' => 'required',
            'event_type_id' => 'required',
            'player_id' => 'nullable',
            'minute' => 'numeric|nullable',
            'active' => 'boolean|nullable',
        ];

        $validator = Validator::make($params, $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        } else {
            return $params;
        }
    }
}