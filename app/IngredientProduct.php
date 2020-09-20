<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IngredientProduct extends Model {

    public $timestamps = false;
    protected $fillable = ["product_id", "text", "lang"];

}
