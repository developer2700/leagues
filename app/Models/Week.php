<?php
 

namespace  App\Models;

class Week extends InsiderModel
{

    /**
     * TABLE NAME
     *
     * @var string
     */
    protected $table = 'weeks';

    /**
     * PRIMARY KEY NAME
     *
     * @var string
     */
    protected $primaryKey = 'week_id';

    /**
     * TABLES ITEMS
     *
     * @var array
     */
    protected $fillable = ['name', 'league_id'];

    /**
     * RETURN WEEK LEAGUE
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function league()
    {
        return $this->belongsTo(League::class, 'league_id', 'league_id');
    }

    /**
     * RETURN WEEK MATCHES
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matches()
    {
        return $this->hasMany(Match::class, 'week_id', 'week_id')->with(['hostTeam', 'guestTeam']);
    }
}