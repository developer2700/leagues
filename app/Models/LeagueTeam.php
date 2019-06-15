<?php
 

namespace App\Models;

class LeagueTeam extends InsiderModel
{

    /**
     * TABLE NAME
     *
     * @var string
     */
    protected $table = 'leagues_teams';

    /**
     * PRIMARY KEY NAME
     *
     * @var string
     */
    protected $primaryKey = 'league_team_id';

    /**
     * TABLES ITEMS
     *
     * @var array
     */
    protected $fillable = ['league_id', 'team_id', 'played', 'won', 'drawn', 'lost', 'gf', 'ga', 'gd', 'Points', 'percent'];

    /**
     * RETURN LEAGUE
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function league()
    {
        return $this->belongsTo(League::class, 'league_id', 'league_id');
    }

    /**
     * RETURN TEAM
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id', 'team_id');
    }

}