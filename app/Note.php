<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'body', 'draft_id'];

    public function draft()
    {
        return $this->belongsTo('App\Draft');
    }

}
