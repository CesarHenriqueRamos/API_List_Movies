<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movies extends Model
{
    use HasFactory;
    protected $fillable = ['year', 'title', 'studios', 'producers', 'winner'];   
    public $timestamps = false;
}
