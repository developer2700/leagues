<?php
 
namespace App\Services;


use Illuminate\Container\EntryNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use App\Exceptions\DuplicateDataException;
use App\Repository\Mysql\RepositoryMatch;
use \Illuminate\Support\Facades\Validator;

class MatchService extends BaseService
{

    private $Repository;

    public function __construct(RepositoryMatch $repository = null)
    {
        if (!$repository) {
            $repository = new RepositoryMatch();
        }
        $this->Repository = $repository;
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
     * @throws DuplicateDataException
     */
    public function set(array $params): Collection
    {
        $params = $this->validate($params);
        $result = $this->Repository->set($params);
        $this->calEvents($result);
        return $result;
    }

    /**
     * UPDATE MODEL BY ID
     *
     * @param array $params
     * @param int $iId
     *
     * @param bool $bSoftUpdate
     * @return Collection
     * @throws DuplicateDataException
     * @throws EntryNotFoundException
     * @throws ValidationException
     */
    public function update(array $params, int $iId, $bSoftUpdate = false): Collection
    {
        $params = $this->validate($params);
        $params['match_id'] = $iId;
        $result = $this->Repository->update($params);
        $this->calEvents($result, $bSoftUpdate);
        return $result;
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
        $Match = $this->get($iId);
        $this->calEvents($Match);
        return $this->Repository->delete($iId);
    }

    public function checkWeekIsEnd($iId): bool
    {
        return $this->Repository->checkWeekIsEnd($iId);
    }

    /**
     * VALIDATE MODEL ATTRIBUTES
     *
     * @param array $params
     *
     * @return array
     * @throws ValidationException
     * @throws \Illuminate\Container\EntryNotFoundException
     * @throws DuplicateDataException
     */
    private function validate(array $params): array
    {

        $TeamService = new TeamService();
        $WeekService = new WeekService();

        if (array_key_exists('host_team_id', $params)) {
            $TeamService->get($params['host_team_id']);
        }

        if (array_key_exists('guest_team_id', $params)) {
            $TeamService->get($params['guest_team_id']);
        }

        if (array_key_exists('week_id', $params)) {
            $WeekService->get($params['week_id']);
        }


        if (array_key_exists('host_team_id', $params) && array_key_exists('guest_team_id', $params)) {
            if ($params['host_team_id'] === $params['guest_team_id']) {
                throw new DuplicateDataException();
            }
        }

        $rules = [
            'week_id' => 'required',
            'host_team_id' => 'required',
            'guest_team_id' => 'required',
            'host_result' => 'numeric|nullable',
            'guest_result' => 'numeric|nullable',
            'start_at' => 'date|nullable',
            'end_at' => 'date|nullable',
        ];

        $validator = Validator::make($params, $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        } else {
            return $params;
        }
    }

    /**
     * FIX MATCH EVENTS
     *
     * @param Collection $match
     * @param bool $bSoftUpdate
     * @return bool
     */
    public function calEvents(Collection $match, $bSoftUpdate = false)
    {

        $EventService = new EventService();
        $TeamService = new TeamService();
        $LeagueService = new LeagueService;

        $hostResult = $match->get('host_result') ?? 0;
        $gustResult = $match->get('guest_result') ?? 0;

        if (!$bSoftUpdate) {
            try {
                $matchEvents = $EventService->getByMatch($match->get('match_id'));
                foreach ($matchEvents as $matchEvent) {
                    $EventService->delete(collect($matchEvent)->get('event_id'));
                }
                for ($i = 1; $i <= $hostResult; $i++) {
                    try {
                        $team = $TeamService->get($match->get('host_team_id'));
                        $players = collect($team->get('players'));
                        if ($players->count() > 0) {
                            $player = collect($players->random());
                            $player_id = $player->get('player_id');
                        } else {
                            throw new EntryNotFoundException;
                        }
                    } catch (EntryNotFoundException $e) {
                        $player_id = null;
                    }

                    try {
                        $EventService->set([
                            'match_id' => $match->get('match_id'),
                            'event_type_id' => 1,
                            'player_id' => $player_id,
                            'minute' => rand(1, 90),
                            'active' => false
                        ]);
                    } catch (EntryNotFoundException $e) {
                    } catch (ValidationException $e) {
                    }
                }

                for ($i = 1; $i <= $gustResult; $i++) {
                    try {
                        $team = $TeamService->get($match->get('guest_team_id'));
                        $players = collect($team->get('players'));
                        if ($players->count() > 0) {
                            $player = collect($players->random());
                            $player_id = $player->get('player_id');
                        } else {
                            throw new EntryNotFoundException;
                        }
                    } catch (EntryNotFoundException $e) {
                        $player_id = null;
                    }

                    try {
                        $EventService->set([
                            'match_id' => $match->get('match_id'),
                            'event_type_id' => 2,
                            'player_id' => $player_id,
                            'minute' => rand(1, 90),
                            'active' => false
                        ]);
                    } catch (EntryNotFoundException $e) {
                    } catch (ValidationException $e) {
                    }
                }

            } catch (EntryNotFoundException $e) {
            }
        }

        try {
            $match = $this->get($match->get('match_id'));
            $week = collect($match->get('week'));
            $league_id = collect($week->get('league'))->get('league_id');
            $LeagueService->calTeam($league_id);
        } catch (EntryNotFoundException $e) {
        } catch (ValidationException $e) {
        }
        return true;
    }

    /**
     * GET MATCH POINTS
     *
     * @param $iId
     *
     * @return Collection
     * @throws EntryNotFoundException
     */
    public function getMatchPoints($iId)
    {
        $match = $this->get($iId);
        $hostResult = $match->get('host_result') ?? 0;
        $guestResult = $match->get('guest_result') ?? 0;
        $hostPoint = ($hostResult === $guestResult ? 1 : ($hostResult > $guestResult ? 3 : 0));
        $guestPoint = ($hostResult === $guestResult ? 1 : ($hostResult < $guestResult ? 3 : 0));
        $match->put('host_point', $hostPoint);
        $match->put('guest_point', $guestPoint);
        return $match;
    }
}