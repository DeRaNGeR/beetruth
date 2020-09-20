<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductScore extends Model {

    public $timestamps = false;

    protected $fillable = [
        "product_id",
        "fvpn_total_percentage",
        "fvpn_total_percentage_estimated",
        "fruit_percentage",
        "vegetable_percentage",
        "pulses_percentage",
        "nuts_percentage",
        "fruit_percentage_dried",
        "vegetable_percentage_dried",
        "pulses_percentage_dried",
        "ofcom_n_energy_kj",
        "ofcom_n_saturated_fat",
        "ofcom_n_sugars",
        "ofcom_n_salt",
        "ofcom_p_protein",
        "ofcom_p_fvpn",
        "ofcom_p_dietary_fiber",
        "ofcom_n_energy_kj_mixed",
        "ofcom_n_saturated_fat_mixed",
        "ofcom_n_sugars_mixed",
        "ofcom_n_salt_mixed",
        "ofcom_p_protein_mixed",
        "ofcom_p_fvpn_mixed",
        "ofcom_p_dietary_fiber_mixed",
    ];

}
