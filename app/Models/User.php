<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Wave\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'verification_code',
        'verified',
        'gearman_ip',
        'trial_ends_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'trial_ends_at' => 'datetime',
    ];
    public function canImpersonate(){

    }
    public function brokers()
    {
        return $this->hasMany(Broker::class);
    }
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
    public function stocks()
    {
        return $this->hasMany(stock::class);
    }
    public function messages()
    {
        return $this->hasMany(messages::class,"user_id","id");
    }
    public function to_messages()
    {
        return $this->hasMany(messages::class,"to_user_id","id");
    }
    public function chats()
    {
        return $this->hasMany(chat::class,"user_id","id");
    }
    public function to_chats()
    {
        return $this->hasMany(chat::class,"to_user_id","id");
    }

    public function user_profile()
    {
        return $this->hasOne(messages::class,"to_user_id","id");
    }
}
