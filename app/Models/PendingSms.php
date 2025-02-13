<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendingSms extends Model
{
    use HasFactory;
    protected $table="pending_sms";
    protected $guarded=["id"];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
