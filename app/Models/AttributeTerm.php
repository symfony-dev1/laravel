<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class AttributeTerm extends Model
{
    use HasFactory, Sortable;
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'attribute_term_product');
    }

    public function variants()
    {
        return $this->belongsToMany(Variant::class, 'attribute_term_variant');
    }
}
