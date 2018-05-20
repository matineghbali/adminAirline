<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    protected $fillable=[
        'user_id','type','gender','fname','lname','doc_id','birthday','price','reserve'
    ];

    public function user(){
        return $this->belongsToMany(User::class);
    }

    public function tickets(){
        return $this->hasMany(Ticket::class);
    }

}
