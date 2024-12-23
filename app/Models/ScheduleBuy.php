<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleBuy extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table='schedule_buy';
    protected $connection = 'sqlite_schedule'; // Specify SQLite connection

    public static function setServerTime(ScheduleBuy $model)
    {
        // Convert user time to server time (assuming server uses UTC)
        $serverTime = Carbon::create($model->date, $model->time, $model->timezone)->setTimezone('UTC');

        // Update the model with the server time
        $model->server_time = $serverTime->format('H:i:s');
        $model->save();
    }

    // If you want to apply this to newly created models automatically
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            ScheduleBuy::setServerTime($model);
        });
    }
}
