<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;


class Tag extends Model
{
    use HasFactory, Sortable;

    public $sortable = ['title', 'slug', 'description', 'created_at', 'updated_at'];

    public function products()
    {
        return $this->belongsToMany(Product::class, "product_tag");
    }
}
