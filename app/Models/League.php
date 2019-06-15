<?php
 
namespace  App\Models;

class League extends InsiderModel
{

    /**
     * TABLE NAME
     *
     * @var string
     */
    protected $table = 'leagues';

    /**
     * PRIMARY KEY NAME
     *
     * @var string
     */
    protected $primaryKey = 'league_id';

    /**
     * TABLES ITEMS
     *
     * @var array
     */
    protected $fillable = ['name', 'nowWeek'];

    /**
     * RETURN LEAGUE WEEKS
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function weeks()
    {
        return $this->hasMany(Week::class, 'league_id', 'league_id')->with(['matches']);
    }

    /**
     * RETURN LEAGUE TEAMS
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leagueTeam()
    {
        return $this->hasMany(LeagueTeam::class, 'league_id', 'league_id')->with(['team']);
    }

    /**
     * RETURN LEAGUE TEAMS
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'leagues_teams', 'league_id', 'team_id')->with(['players']);
    }
}