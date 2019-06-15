<?php
 

namespace  App\Models;

class Event extends InsiderModel
{

    /**
     * TABLE NAME
     *
     * @var string
     */
    protected $table = 'events';

    /**
     * PRIMARY KEY NAME
     *
     * @var string
     */
    protected $primaryKey = 'event_id';

    /**
     * TABLES ITEMS
     *
     * @var array
     */
    protected $fillable = ['match_id', 'event_type_id', 'player_id', 'minute', 'active'];

    /**
     * RETURN WEEK MATCH
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function match()
    {
        return $this->belongsTo(Match::class, 'week_id', 'week_id');
    }

    public function eventType()
    {
        return $this->belongsTo(Setting::class, 'event_type_id', 'setting_id');
    }

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id', 'player_id');
    }
}