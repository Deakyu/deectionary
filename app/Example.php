<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Example extends Model
{
    public $fillable = [
        'example',
        'user_id'
    ];

    public function users() {
        return $this->belongsTo(User::class);
    }
}
