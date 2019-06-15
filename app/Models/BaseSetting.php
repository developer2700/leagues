<?php
 
namespace App\Models;


class  BaseSetting extends InsiderModel
{

    /**
     * TABLE NAME
     *
     * @var string
     */
    protected $table = 'base_settings';

    /**
     * PRIMARY KEY NAME
     *
     * @var string
     */
    protected $primaryKey = 'base_setting_id';

    /**
     * TABLES ITEMS
     *
     * @var array
     */
    protected $fillable = ['parent_id', 'title', 'description'];

    /**
     * GET PARENT BASE SETTING
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(BaseSetting::class, 'parent_id', 'base_setting_id');
    }

    /**
     * GET CHILDREN BASE SETTINGS
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(BaseSetting::class, 'parent_id', 'base_setting_id');
    }

    /**
     * GET SETTINGS
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function settings()
    {
        return $this->hasMany(Setting::class, 'base_setting_id', 'base_setting_id');
    }


}