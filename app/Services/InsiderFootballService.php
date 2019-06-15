<?php
 

namespace  App\Services;

use Illuminate\Container\EntryNotFoundException;
use Illuminate\Validation\ValidationException;
use App\Exceptions\DuplicateDataException;

class InsiderFootballService
{


    /**
     * START WEEK MATCHES
     *
     * @throws \Illuminate\Container\EntryNotFoundException
     * @throws \Illuminate\Validation\ValidationException
     * @throws App\Exceptions\DuplicateDataException
     */
    public function startPlayWeek()
    {

        $WeekService = new WeekService();
        $LeagueService = new LeagueService();
        $MatchService = new MatchService();
        $EventService = new EventService();

        $league = $LeagueService->get(1);
        $week = $WeekService->getByName($league->get('nowWeek'));
        foreach ($week->get('matches') as $match) {
            $EventService->deActiveByMatchId($match['match_id']);
            $MatchService->update([
                'week_id' => $match['week_id'],
                'host_team_id' => $match['host_team_id'],
                'guest_team_id' => $match['guest_team_id'],
                'host_result' => 0,
                'guest_result' => 0,
                'start_at' => now(),
                'end_at' => null,
            ], $match['match_id'], true);
        }
        return true;
    }

    /**
     * CHECK WEEK EVENTS
     *
     * @throws \Illuminate\Container\EntryNotFoundException
     * @throws \Illuminate\Validation\ValidationException
     * @throws App\Exceptions\DuplicateDataException
     */
    public function checkWeek()
    {


            $WeekService = new WeekService();
            $LeagueService = new LeagueService();
            $MatchService = new MatchService();
            $EventService = new EventService();

            $league = $LeagueService->get(1);
            $week = $WeekService->getByName($league->get('nowWeek'));

            foreach ($week->get('matches') as $match) {
                $events = $EventService->getByMatch($match['match_id']);
                $events = collect($events)->where('active', '===', 0);
                if (count($events)) {
                    $startDate = strtotime($match['start_at']);
                    $nowDate = strtotime(now());
                    $minute = round(abs($nowDate - $startDate) / 5, 0) . " minute";
                    $events = collect($events)->where('active', '=', 0)->where('minute', '<', $minute);
                    foreach ($events as $event) {
                        if ($event['event_type_id'] === 1) {
                            $MatchService->update([
                                'week_id' => $match['week_id'],
                                'host_team_id' => $match['host_team_id'],
                                'guest_team_id' => $match['guest_team_id'],
                                'host_result' => $match['host_result'] + 1,
                                'guest_result' => $match['guest_result'],
                                'start_at' => $match['start_at'],
                                'end_at' => $match['end_at'],
                            ], $match['match_id'], true);
                        } else {
                            $MatchService->update([
                                'week_id' => $match['week_id'],
                                'host_team_id' => $match['host_team_id'],
                                'guest_team_id' => $match['guest_team_id'],
                                'host_result' => $match['host_result'],
                                'guest_result' => $match['guest_result'] + 1,
                                'start_at' => $match['start_at'],
                                'end_at' => $match['end_at'],
                            ], $match['match_id'], true);
                        }
                        $EventService->update([
                            "match_id" => $event["match_id"],
                            "event_type_id" => $event["event_type_id"],
                            "player_id" => $event["player_id"],
                            "minute" => $event["minute"],
                            "active" => 1,
                        ], $event['event_id']);
                    }
                } else {
                    $MatchService->update([
                        'week_id' => $match['week_id'],
                        'host_team_id' => $match['host_team_id'],
                        'guest_team_id' => $match['guest_team_id'],
                        'host_result' => $match['host_result'],
                        'guest_result' => $match['guest_result'],
                        'start_at' => $match['start_at'],
                        'end_at' => now(),
                    ], $match['match_id'], true);
                }
            }

        return true;
    }

    /**
     * CHECK ALL WEEKS MATCHES IS END
     *
     * @return bool
     *
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function endPlayWeek()
    {

        $WeekService = new WeekService();
        $LeagueService = new LeagueService();
        $MatchService = new MatchService();

        $league = $LeagueService->get(1);
        $week = $WeekService->getByName($league->get('nowWeek'));
        $result =  $MatchService->checkWeekIsEnd($week['week_id']);

        if(!$result){
                try {
                    $this->checkWeek();
                } catch (EntryNotFoundException $e) {
                } catch (ValidationException $e) {
                } catch (DuplicateDataException $e) {
                }

        }

        return $result;
    }
}