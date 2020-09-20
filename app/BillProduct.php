<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillProduct extends Model {

    public $timestamps = false;
    protected $fillable = ["bill_id", "product_id"];

    public function product() {
        return $this->hasOne("App\Product", "id", "product_id");
    }

}
