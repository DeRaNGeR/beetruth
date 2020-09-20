<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AllergenProduct extends Model {

    public $timestamps = false;

    protected $fillable = ["product_id", "name"];

}
