<?php
 
namespace  App\Models;

class Player extends InsiderModel
{

    /**
     * TABLE NAME
     *
     * @var string
     */
    protected $table = 'players';

    /**
     * PRIMARY KEY NAME
     *
     * @var string
     */
    protected $primaryKey = 'player_id';

    /**
     * TABLES ITEMS
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * RETURN PLAYER CONTRACTS
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contracts()
    {
        return $this->hasMany(Contract::class, 'player_id', 'player_id')->where('end_at', '!=', null);
    }

    /**
     * RETURN PLAYER TEAMS
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'contracts', 'player_id', 'team_id');
    }
}