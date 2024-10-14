<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchivedStock extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table='archived_accounts';
}
