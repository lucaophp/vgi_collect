<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventRep extends Model
{
    protected $table = 'event_rep';
    public $timestamps = false;
    protected $fillable = [
        'type_ev_id','datahora','latitude','longitude','photo','status','user_id'
    ];
    protected $hidden = [
        'user_id'
    ];

    public function typeEv()
    {
        return $this->belongsTo(TypeEv::class,'type_ev_id')->select();
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
