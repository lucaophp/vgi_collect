<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeEv extends Model
{
    protected $table = 'type_ev';
    public $timestamps = false;
    protected $fillable = ['description','name'];
    public function events()
    {
        return $this->hasMany(Event::class,'type_ev_id');
    }
    public function eventsRep()
    {
        return $this->hasMany(EventRep::class,'type_ev_id');
    }

}
