<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product_sale extends Model
{
    protected $table = 'product_sales';

    //
    protected $fillable = [
        'sale_id',
        'customer_id',
        'product_id',
        'quantity',
        'price',
        'payment_method',
        'created_at',
        'updated_at'
    ];

}
