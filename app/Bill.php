<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model {

    public $timestamps = false;
    protected $fillable = ["user_id", "nutri_score", "nutri_score_final"];

    public function products() {
        return $this->hasMany('App\BillProduct');
    }

    public function replacements() {
        return $this->hasMany('App\Replacement');
    }

}
