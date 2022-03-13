<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public $timestamps=false;
    protected $fillable = [
        'brand',
        'category_id',
        'sub_category_id',
        'name',
        'description',
        'short_desc',
        'product_size',
        'color',
        'product_qty',
        'selling_price',
        'discount_price',
        'image',
        'status',
        'avgRate'
    ];
    public function category(){
        return $this->belongsTo(Category::class,'category_id', 'id');
    }
    public function Subcategory(){
        return $this->belongsTo(SubCategory::class,'sub_category_id', 'id');
    }
}
