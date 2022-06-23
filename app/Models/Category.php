<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'categories';

    public function Movies(){
        return $this->hasMany(Movie::class);
    }

//    public function categoriesUser(){
//        return $this->belongsTo(Category::class,'category_id');
//    }

}
