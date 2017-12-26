<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    //
    public $fillable = [
        'word', 'pronunciation',
    ];

    public function users() {
        return $this->belongsToMany(User::class)->withPivot('user_id', 'word_id', 'type', 'created_at', 'updated_at')
            ->withTimestamps();
    }

    public function definitions() {
        return $this->hasMany(Definition::class);
    }

    public function getTypeAttribute() {
        return $this->pivot->type;
    }
}
