<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productRcm extends Model
{
    use HasFactory;

    protected $table = "product_rcms";

    public function product(){
        return $this->belongsTo(Product::class, 'idProductRcm0');
    }
}
