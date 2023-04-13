<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    // protected $casts = [
    //     'id' => 'uuid',
    // ];
    use HasFactory;
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
}
