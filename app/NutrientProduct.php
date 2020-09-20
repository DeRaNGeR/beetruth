<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NutrientProduct extends Model {

    public $timestamps = false;

    protected $fillable = ["product_id", "name", "amount", "unit_of_measure"];

}
