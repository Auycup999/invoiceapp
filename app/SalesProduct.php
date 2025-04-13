<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesProduct extends Model
{
  

    // กำหนดฟิลด์ที่สามารถกรอกข้อมูลได้
    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'price',
    ];

    // การเชื่อมโยงกับตาราง 'sales'
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    // การเชื่อมโยงกับตาราง 'products'
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
