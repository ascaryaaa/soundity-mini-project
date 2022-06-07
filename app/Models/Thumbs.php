<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thumbs extends Model
{
    protected $fillable = ['user_id', 'post_id', 'type'];
    public $timestamps = false;
    use HasFactory;
}
