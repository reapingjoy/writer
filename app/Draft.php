<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Draft extends Model
{

    use SoftDeletes;

    protected $fillable = ['title', 'alias', 'short_description', 'user_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function notes()
    {
        return $this->hasMany('App\Note');
    }

    public function shared_users()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }
}
