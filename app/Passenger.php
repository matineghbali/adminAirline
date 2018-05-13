<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Passenger extends Model
{
    protected $fillable=[
        'type','gender','fname','lname','doc_id','birthday','reserve'
    ];

    public function user(){
        return $this->belongsToMany(User::class);
    }

}
