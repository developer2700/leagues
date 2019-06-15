<?php
 
namespace  App\Models;

class Match extends InsiderModel
{

    /**
     * TABLE NAME
     *
     * @var string
     */
    protected $table = 'matches';

    /**
     * PRIMARY KEY NAME
     *
     * @var string
     */
    protected $primaryKey = 'match_id';

    /**
     * TABLES ITEMS
     *
     * @var array
     */
    protected $fillable = ['week_id', 'host_team_id', 'guest_team_id', 'host_result', 'guest_result', 'start_at', 'end_at'];

    public function week()
    {
        return $this->belongsTo(Week::class, 'week_id', 'week_id')->with(['league']);
    }

    /**
     * RETURN HOST TEAM
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hostTeam()
    {
        return $this->belongsTo(Team::class, 'host_team_id', 'team_id');
    }

    /**
     * RETURN GUEST TEAM
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function guestTeam()
    {
        return $this->belongsTo(Team::class, 'guest_team_id', 'team_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'match_id', 'match_id');
    }
}