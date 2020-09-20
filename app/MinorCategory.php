<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MinorCategory extends Model {

    public $timestamps = false;

    protected $fillable = ["id", "name_de", "name_en", "name_it", "name_fr", "nutri_score_category", "major_category_id", "icon"];

}
