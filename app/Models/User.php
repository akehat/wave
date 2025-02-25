<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Wave\User as Authenticatable;
use Laravel\Cashier\Billable;
class User extends Authenticatable
{
    use Billable;
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
        return $this->hasMany(Stock::class);
    }
    public function messages()
    {
        return $this->hasMany(Message::class,"user_id","id");
    }
    public function to_messages()
    {
        return $this->hasMany(Message::class,"to_user_id","id");
    }
    public function chats()
    {
        return $this->hasMany(Chat::class,"user_id","id");
    }
    public function to_chats()
    {
        return $this->hasMany(Chat::class,"to_user_id","id");
    }

    public function user_profile()
    {
        return $this->hasOne(UserProfile::class,"user_id","id");
    }
}
