<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{


    // If you have a custom table name, specify it here
    protected $table = 'customers';

    // If you want to define the fields that are mass assignable
    protected $fillable = [
        'customer_code',
        'customer_name',
        'contact_person',
        'address',
        'phone',
        'type',
        'credit_limit',
        'notes',
    ];

    // If the timestamps are not needed, you can disable them
    // public $timestamps = false;
}
