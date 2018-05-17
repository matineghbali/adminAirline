<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $fillable=[
        'DepartureAirport',
        'ArrivalAirport',
        'DepartureDateTime',
        'ArrivalDateTime',
        'AvailableSeatQuantity',
        'FlightNumber',
        'FareBasisCode',
        'MarketingAirline',
        'cabinType',
        'AirEquipType',
        'price',
        'ADTPrice',
        'CHDPrice',
        'INFPrice',
    ];

    public function tickets(){
        return $this->hasMany(Ticket::class);
    }
}
