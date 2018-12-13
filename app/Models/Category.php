<?php

namespace App\Models;

use App\Observers\ReplyObserver;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name', 'description',
    ];

    public function topic()
    {
        return  $this->hasMany(Topic::class);
    }

    public function reply()
    {
        return  $this->hasMany(Reply::class);
    }
}
