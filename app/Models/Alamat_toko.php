<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alamat_toko extends Model
{
    protected $table = 'alamat_toko';
    protected $fillable = ['city_id','detail'];
}
