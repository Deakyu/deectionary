<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Definition extends Model
{
    //
    public $fillable = [
        'definition',
        'form',
        'example',
        'word_id'
    ];

    public function word() {
        return $this->belongsTo(Word::class);
    }
}
