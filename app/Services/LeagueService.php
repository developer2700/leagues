<?php
 

namespace App\Services;


use Illuminate\Container\EntryNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use App\Repository\Mysql\RepositoryLeague;
use \Illuminate\Support\Facades\Validator;

class LeagueService extends BaseService
{

    private $Repository;

    public function __construct(RepositoryLeague $repository = null)
    {
        $this->Repository = $repository ?? new RepositoryLeague();
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
     */
    public function set(array $params): Collection
    {
        $params = $this->validate($params);
        $result = $this->Repository->set($params);
        try {
            $this->calTeam($result->get('league_id'));
        } catch (EntryNotFoundException $e) {
        } catch (ValidationException $e) {
        }
        return $result;
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
        $params['league_id'] = $iId;
        $result = $this->Repository->update($params);
        try {
            $this->calTeam($result->get('league_id'));
        } catch (EntryNotFoundException $e) {
        } catch (ValidationException $e) {
        }
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
        try {
            $this->calTeam($iId);
        } catch (EntryNotFoundException $e) {
        } catch (ValidationException $e) {
        }
        return $this->Repository->delete($iId);
    }

    /**
     * VALIDATE MODEL ATTRIBUTES
     *
     * @param array $params
     *
     * @return array
     * @throws ValidationException
     */
    private function validate(array $params): array
    {

        $rules = [
            'name' => 'required',
            'nowWeek' => 'nullable'
        ];

        $validator = Validator::make($params, $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        } else {
            return $params;
        }
    }

    /**
     * CALCULATOR TEAMS POINT
     *
     * @param int $iId
     *
     * @return bool
     * @throws EntryNotFoundException
     * @throws ValidationException
     */
    public function calTeam(int $iId)
    {

        $LeagueTeamService = new LeagueTeamService();
        $EventService = new EventService();
        $MatchService = new MatchService();

        $league = $this->get($iId);
        $weeks = $league->get('weeks');
        $thisWeek = false;
        $aWeek = false;


        $leagueTeams = collect($LeagueTeamService->getModelByLeagueId($iId));

        foreach ($leagueTeams as $leagueTeam) {
            $leagueTeam->played = 0;
            $leagueTeam->won = 0;
            $leagueTeam->drawn = 0;
            $leagueTeam->lost = 0;
            $leagueTeam->gf = 0;
            $leagueTeam->ga = 0;
            $leagueTeam->gd = 0;
            $leagueTeam->Points = 0;
            $leagueTeam->percent = 0;
            $LeagueTeamService->update($leagueTeam->toArray(), $leagueTeam->league_team_id);
        };

        foreach ($weeks as $week) {
            $week = collect($week);

            if ($thisWeek) {
                $aWeek = true;
            }

            if ($week->get('name') === $league->get('nowWeek')) {
                $thisWeek = true;
            }

            $matches = $week->get('matches');
            if ($aWeek) {
                foreach ($matches as $match) {
                    $match = collect($match);
                    $EventService->deActiveByMatchId($match->get('match_id'));
                }
            } else {
                foreach ($matches as $match) {
                    $match = collect($match);
                    $match = $MatchService->getMatchPoints($match->get('match_id'));

                    $leagueTeams = $LeagueTeamService->getModelByLeagueId($iId);
                    $leagueTeam = $leagueTeams->where('team_id', $match->get('host_team_id'))->first();
                    $leagueTeam->played = (++$leagueTeam->played);
                    $leagueTeam->won = ($match->get('host_point') == 3 ? ++$leagueTeam->won : $leagueTeam->won);
                    $leagueTeam->drawn = ($match->get('host_point') == 1 ? ++$leagueTeam->drawn : $leagueTeam->drawn);
                    $leagueTeam->lost = ($match->get('host_point') == 0 ? ++$leagueTeam->lost : $leagueTeam->lost);
                    $leagueTeam->gf = ($leagueTeam->gf + $match->get('host_result'));
                    $leagueTeam->ga = ($leagueTeam->ga + $match->get('guest_result'));
                    $leagueTeam->gd = ($leagueTeam->gd + ($match->get('host_result') - $match->get('guest_result')));
                    $leagueTeam->Points = ($match->get('host_point') + $leagueTeam->Points);
                    $LeagueTeamService->update($leagueTeam->toArray(), $leagueTeam->league_team_id);

                    $leagueTeams = $LeagueTeamService->getModelByLeagueId($iId);
                    $leagueTeam = $leagueTeams->where('team_id', $match->get('guest_team_id'))->first();
                    $leagueTeam->played = (++$leagueTeam->played);
                    $leagueTeam->won = ($match->get('guest_point') == 3 ? ++$leagueTeam->won : $leagueTeam->won);
                    $leagueTeam->drawn = ($match->get('guest_point') == 1 ? ++$leagueTeam->drawn : $leagueTeam->drawn);
                    $leagueTeam->lost = ($match->get('guest_point') == 0 ? ++$leagueTeam->lost : $leagueTeam->lost);
                    $leagueTeam->gf = ($leagueTeam->gf + $match->get('guest_result'));
                    $leagueTeam->ga = ($leagueTeam->ga + $match->get('host_result'));
                    $leagueTeam->gd = ($leagueTeam->gd + ($match->get('guest_result') - $match->get('host_result')));
                    $leagueTeam->Points = ($match->get('guest_point') + $leagueTeam->Points);
                    $LeagueTeamService->update($leagueTeam->toArray(), $leagueTeam->league_team_id);

                }
            }
        }

        $leagueTeams = collect($LeagueTeamService->getModelByLeagueId($iId));
        foreach ($leagueTeams as $leagueTeam) {
            $point = $leagueTeam->Points;
            $sum = $leagueTeams->sum('Points');
            $leagueTeam->percent = $point * 100 / ($sum ? $sum : 1);
            $LeagueTeamService->update($leagueTeam->toArray(), $leagueTeam->league_team_id);
        };
        return true;
    }
}