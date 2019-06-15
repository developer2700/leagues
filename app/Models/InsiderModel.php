<?php
 
namespace  App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class InsiderModel extends Model {

    use SoftDeletes;
    use Notifiable;

    protected $dates = ['deleted_at'];

    /**
     * HIDDEN TABLE ITEMS
     *
     * @var array
     */
    protected $hidden = [
        'created_at' ,'updated_at','deleted_at'
    ];


}