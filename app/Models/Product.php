<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $table = 'products';
    protected $fillable = ['name', 'image', 'description', 'price', 'weigth', 'categories_id', 'stok'];

    public function category()
    {
        return $this->belongsTo(Categories::class, 'categories_id');
    }
}
