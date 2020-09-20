<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MajorCategory extends Model {

    public $timestamps = false;

    protected $fillable = ["id", "name_de", "name_en", "name_it", "name_fr"];

}
