<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variant extends Model
{
    use HasFactory;
    public function variant_attribute_terms()
    {
        return $this->belongsToMany(AttributeTerm::class, 'attribute_term_variant');
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
