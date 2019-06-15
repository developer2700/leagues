<?php
 
namespace  App\Models;

class Contract extends InsiderModel
{

    /**
     * TABLE NAME
     *
     * @var string
     */
    protected $table = 'contracts';

    /**
     * PRIMARY KEY NAME
     *
     * @var string
     */
    protected $primaryKey = 'contract_id';

    /**
     * TABLES ITEMS
     *
     * @var array
     */
    protected $fillable = ['player_id', 'team_id', 'start_at', 'end_at'];

    /**
     * RETURN CONTRACT TEAM
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id', 'team_id');
    }

    /**
     * RETURN CONTRACT PLAYER
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id', 'player_id');
    }


}