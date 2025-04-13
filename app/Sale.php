<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'customer_id',
        'invoice_number',
        'payment_method',
        'total',
        'due_date',
    ];
    public function products()
{
    return $this->belongsToMany(Product::class, 'sales_products')
                ->withPivot('quantity', 'price')
                ->withTimestamps();
}
public function customer()
{
    return $this->belongsTo(Customer::class);
}
}

