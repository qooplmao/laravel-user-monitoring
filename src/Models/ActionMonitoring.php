<?php

namespace Binafy\LaravelUserMonitoring\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionMonitoring extends Model
{
    use HasFactory;

    /**
     * Guarded columns.
     *
     * @var array
     */
    protected $guarded = ['id'];

    # Relations

    public function user()
    {
        return $this->belongsTo(config('user-monitoring.user.model'), config('user-monitoring.user.foreign_key'));
    }

    public function getTable()
    {
        return config('user-monitoring.action_monitoring.table', 'actions_monitoring');
    }
}
