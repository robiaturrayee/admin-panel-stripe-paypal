<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'image'
    ];

    public function specifications()
    {
        return $this->hasMany(ProductSpecification::class);
    }
}
