<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sales_products extends Model
{
    protected $fillable = [
       'sale_id',
        'customer_id',
        'product_id',
        'name',
        'quantity',
        'price',
        'payment_method',
        'total',
        'created_at',
        'updated_at'
    ];
}
