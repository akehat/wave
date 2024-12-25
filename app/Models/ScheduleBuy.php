<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Cache;

class ScheduleBuy extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table='schedule_buy';

    public static function setServerTime(ScheduleBuy $model)
    {
        // Convert user time to server time (assuming server uses UTC)
        $serverTime = Carbon::parse("{$model->date} {$model->time}", $model->timezone)->setTimezone('UTC');
        // Update the model with the server time
        $model->server_time = $serverTime->format('H:i:s');
    }

    // If you want to apply this to newly created models automatically
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            ScheduleBuy::setServerTime($model);
        });
        
        static::updated(function () {
            ScheduleBuy::setServerTime($model);
        });
    }
    public function save(array $options = [])
    {
        // Save the model
        parent::save($options);

        // Cache all schedule buys
        $this->cacheAllThis();
    }
    public function cacheAllThis()
    {
        $allBuys = ScheduleBuy::all()->toArray();
        Cache::forever('all_schedule_buys', $allBuys);
    }
    public static function cacheAll()
    {
        $allBuys = ScheduleBuy::all()->toArray();
        Cache::forever('all_schedule_buys', $allBuys);
    }

}
