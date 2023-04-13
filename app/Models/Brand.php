<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Brand extends Model
{
    use HasFactory, Sortable;
    public function products()
    {
        return $this->belongsToMany(Product::class, "brand_product");
    }
    public function childs()
    {
        return $this->hasMany(Brand::class, 'parent_id', 'id');
    }
}
