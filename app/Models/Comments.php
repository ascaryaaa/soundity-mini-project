<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    protected $fillable = ['sub_comment', 'comment' ,'user_id', 'post_id'];
    use HasFactory;
}
