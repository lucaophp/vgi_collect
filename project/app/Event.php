<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'event';
    public $timestamps = false;
    protected $fillable = [
        'event_rep_id','user_id','estimative','accept','datahora','obs','level'
    ];
    protected $hidden = [
        'user_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function eventRep()
    {
        return $this->belongsTo(EventRep::class,'event_rep_id');
    }

}
