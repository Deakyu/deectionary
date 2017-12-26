<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'api_token', 'verifyToken'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'api_token', 'verifyToken'
    ];

    public function words() {
        // many to many relationship with words with pivot
        // 'type' is either 'history' or 'marked'
        return $this->belongsToMany(Word::class)->withPivot('user_id', 'word_id', 'type', 'created_at', 'updated_at')
            ->withTimestamps();
    }

    public function examples() {
        return $this->hasMany(Example::class);
    }
}
