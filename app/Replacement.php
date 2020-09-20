<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Replacement extends Model {

    public $timestamps = false;
    protected $fillable = ["id", "product_a_id", "product_b_id", "bill_id"];

    public function product_a() {
        return $this->hasOne("App\Product", "id", "product_a_id");
    }

    public function product_b() {
        return $this->hasOne("App\Product", "id", "product_b_id");
    }

}
