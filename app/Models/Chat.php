<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;
    protected $table="chats";
    protected $guarded=["id"];
    public function to_user()
    {
        return $this->belongsTo(User::class, 'to_user_id', 'id');
    }

    /**
     * Get the "from" user associated with this chat.
     */
    public function from_user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'chat_id', 'id');
    }

}
