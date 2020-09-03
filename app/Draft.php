<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Draft extends Model
{

    use SoftDeletes;

    protected $fillable = ['title', 'alias', 'short_description', 'user_id'];

    //set relationship
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    //set relationship
    public function notes()
    {
        return $this->hasMany('App\Note');
    }

    //set relationship
    public function shared_users()
    {
        return $this->belongsToMany('App\User')->withTimestamps();
    }

    //add local scope
    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    //add observer for the soft deletes
    public static function boot()
    {
        parent::boot();

        self::deleting(function (Draft $draft) {

            foreach ($draft->notes as $note)
            {
                $note->delete();
            }
        });
    }
}
