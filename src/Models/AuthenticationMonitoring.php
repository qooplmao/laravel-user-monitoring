<?php

namespace Binafy\LaravelUserMonitoring\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthenticationMonitoring extends Model
{
    use HasFactory;

    /**
     * Guarded columns.
     *
     * @var string[]
     */
    protected $guarded = ['id'];

    # Relations

    public function user()
    {
        return $this->belongsTo(config('user-monitoring.user.model'), config('user-monitoring.user.foreign_key'));
    }

    public function getTable()
    {
        return config('user-monitoring.authentication_monitoring.table', 'authentications_monitoring');
    }
}
