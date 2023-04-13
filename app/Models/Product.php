<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Variant;
use Kyslik\ColumnSortable\Sortable;


class Product extends Model
{
  use Sortable,
    HasFactory;

  // public $sortable = ['title'];

  public function variants()
  {
    return  $this->hasMany(Variant::class);
  }

  // public function cart_item()
  // {
  //   return  $this->belongsTo(CartItem::class);
  // }

  public function product_attribute_terms()
  {
    return $this->belongsToMany(AttributeTerm::class, 'attribute_term_product');
  }
  public function categories()
  {
    return $this->belongsToMany(Category::class, "category_product");
  }
  public function brands()
  {
    return $this->belongsToMany(Brand::class, "brand_product");
  }
  public function tags()
  {
    return $this->belongsToMany(Tag::class, "product_tag");
  }
}
