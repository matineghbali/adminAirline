<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $fillable=[
        'user_id',
        'FlightNumber',
        'DepartureAirport',
        'ArrivalAirport',
        'DepartureDateTime',
        'ArrivalDateTime',
        'AvailableSeatQuantity',
        'FareBasisCode',
        'MarketingAirlineEN',
        'MarketingAirlineFA',
        'cabinTypeEN',
        'cabinTypeFA',
        'AirEquipType',
        'passengerNumber',
        'price',
        'ADTPrice',
        'CHDPrice',
        'INFPrice',
        'ADTNumber',
        'CHDNumber',
        'INFNumber',
  ];

    public function tickets(){
        return $this->hasMany(Ticket::class);
    }

    public function user(){
        return $this->belongsToMany(User::class);
    }
}
