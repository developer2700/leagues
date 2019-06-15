<?php
 
namespace App\Services;


use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use App\Repository\Mysql\RepositoryLeagueTeam;
use \Illuminate\Support\Facades\Validator;

class LeagueTeamService extends BaseService
{

    private $Repository;

    public function __construct(RepositoryLeagueTeam $repository = null)
    {
        $this->Repository = $repository ?? new RepositoryLeagueTeam();
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
     * GET MODEL DATA BY LEAGUE ID
     *
     * @param int $iId
     *
     * @return Collection
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function getByLeagueId(int $iId): Collection
    {
        return $this->Repository->getByLeagueId($iId);
    }

    /**
     * GET MODEL DATA BY League ID
     *
     * @param int $iId
     *
     * @return Collection
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function getModelByLeagueId(int $iId): Collection
    {
        return $this->Repository->getModelByLeagueId($iId);
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
        $params['league_team_id'] = $iId;
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

        $LeagueService = new LeagueService();
        $TeamService = new TeamService();

        if (array_key_exists('league_id', $params)) {
            $LeagueService->get($params['league_id']);
        }

        if (array_key_exists('team_id', $params)) {
            $TeamService->get($params['team_id']);
        }

        $rules = [
            'league_id' => 'required',
            'team_id' => 'required',
            'played' => 'numeric|nullable',
            'won' => 'numeric|nullable',
            'drawn' => 'numeric|nullable',
            'lost' => 'numeric|nullable',
            'gf' => 'numeric|nullable',
            'ga' => 'numeric|nullable',
            'gd' => 'numeric|nullable',
            'Points' => 'numeric|nullable',
            'percent' => 'numeric|nullable',
        ];

        $validator = Validator::make($params, $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        } else {
            return $params;
        }
    }
}