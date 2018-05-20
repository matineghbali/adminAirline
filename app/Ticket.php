<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable=[
      'passenger_id','flight_id','ticketNumber','dateBook','BookingReference','customer_name','customer_email','customer_tel'
    ];

    public function passenger(){
        return $this->belongsTo(Passenger::class);
    }

    public function flight(){
        return $this->belongsTo(Flight::class);
    }
}
