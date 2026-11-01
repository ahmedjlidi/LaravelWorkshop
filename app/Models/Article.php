<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title', 'journalist', 'deadline'];

    protected function casts(): array
    {
        return ['deadline' => 'datetime'];
    }
}
