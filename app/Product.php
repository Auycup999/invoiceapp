<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{


    protected $fillable = [
        'barcode',
        'name',
        'price',
        'unit'
    ];
    public function sales()
{
    return $this->belongsToMany(Sale::class, 'sales_products')
                ->withPivot('quantity', 'price')
                ->withTimestamps();
}
}
