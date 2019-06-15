<?php
 
namespace  App\Models;

class Team extends InsiderModel
{

    /**
     * TABLE NAME
     *
     * @var string
     */
    protected $table = 'teams';

    /**
     * PRIMARY KEY NAME
     *
     * @var string
     */
    protected $primaryKey = 'team_id';

    /**
     * TABLES ITEMS
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * RETURN TEAM PLAYERS
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function players()
    {
        return $this->belongsToMany(Player::class, 'contracts', 'team_id', 'player_id');
    }

    /**
     * RETURN TEAM LEAGUES
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function leagues()
    {
        return $this->belongsToMany(League::class, 'leagues_teams', 'team_id', 'league_id');
    }

    /**
     * RETURN LEAGUE TEAMS
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leagueTeam()
    {
        return $this->hasMany(LeagueTeam::class, 'team_id', 'team_id')->with(['league']);
    }
}