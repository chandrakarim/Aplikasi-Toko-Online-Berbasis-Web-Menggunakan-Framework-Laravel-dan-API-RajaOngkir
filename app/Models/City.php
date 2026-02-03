<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';
    protected $fillable = ['id', 'province_id', 'title', 'district', 'subdistrict', 'zip_code'];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
