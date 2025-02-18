<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;
    protected $table="user_profiles";
    protected $guarded=["id"];
    public function user()
    {
        return $this->belongsTo(UserProfile::class, 'user_id', 'id');
    }
}
