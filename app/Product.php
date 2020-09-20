<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {

    public $timestamps = false;

    protected $fillable = ["id", "gtin", "product_name_en", "product_name_de", "product_name_fr", "product_name_it", "producer", "product_size", "product_size_unit_of_measure", "serving_size", "comment", "image", "back_image", "major_category_id", "minor_category_id", "source", "source_checked", "health_percentage", "weighted_article", "price", "ofcom_value", "nutri_score_final"];

    protected $hidden = ["created_at", "updated_at"];

    public function allergens() {
        return $this->hasMany('App\AllergenProduct');
    }

    public function ingredients() {
        return $this->hasMany('App\IngredientProduct');
    }

    public function nutrients() {
        return $this->hasMany('App\NutrientProduct');
    }

    public function scores() {
        return $this->hasOne('App\ProductScore');
    }

    protected $casts = [
        'price' => 'float'
    ];

}
