<?php

use Illuminate\Support\Facades\Route;
use GuzzleHttp\Client;
use App\MajorCategory;
use App\MinorCategory;
use App\Product;
use App\AllergenProduct;
use App\IngredientProduct;
use App\NutrientProduct;
use App\ProductScore;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/{any}', function() {
    return view('welcome');
})->where('any', '.*');

// Route::get('/', function () {

//     ini_set('max_execution_time', 30000000);

//     $client = new Client([
//         "base_uri" => "https://eatfit-service.foodcoa.ch",
//         "auth" => [
//             "eatfit_hackzurich",
//             "XmU8G2jeAwYzrU9K"
//         ]
//     ]);

//     $categories = MinorCategory::all();

//     foreach($categories as $category) {

//         $response = $client->request("GET", "products/better-products/category/$category->id?marketRegion=ch&retailer=migros");

//         if($body = $response->getBody()) {

//             $result = json_decode($body);

//             if($result->success) {

//                 foreach($result->products as $entry) {

//                     $product = Product::create([
//                         "gtin" => $entry->gtin,
//                         "product_name_en" => $entry->product_name_en,
//                         "product_name_de" => $entry->product_name_de,
//                         "product_name_fr" => $entry->product_name_fr,
//                         "product_name_it" => $entry->product_name_it,
//                         "producer" => $entry->producer,
//                         "product_size" => $entry->product_size,
//                         "product_size_unit_of_measure" => $entry->product_size_unit_of_measure,
//                         "serving_size" => $entry->serving_size,
//                         "comment" => $entry->comment,
//                         "image" => $entry->image,
//                         "back_image" => $entry->back_image,
//                         "major_category_id" => $entry->major_category,
//                         "minor_category_id" => $entry->minor_category,
//                         "source" => $entry->source,
//                         "source_checked" => $entry->source_checked,
//                         "health_percentage" => $entry->health_percentage,
//                         "weighted_article" => $entry->weighted_article,
//                         "price" => $entry->price,
//                         "ofcom_value" => $entry->ofcom_value,
//                         "nutri_score_final" => $entry->nutri_score_final,
//                     ]);

//                     foreach($entry->allergens as $allergen) {
//                         AllergenProduct::create([
//                             "name" => $allergen->name,
//                             "product_id" => $product->id
//                         ]);
//                     }

//                     foreach($entry->ingredients as $ingredient) {
//                         IngredientProduct::create([
//                             "lang" => $ingredient->lang,
//                             "text" => $ingredient->text,
//                             "product_id" => $product->id
//                         ]);
//                     }

//                     foreach($entry->nutrients as $nutrient) {
//                         if($nutrient->name) {
//                             NutrientProduct::create([
//                                 "name" => $nutrient->name,
//                                 "amount" => $nutrient->amount,
//                                 "unit_of_measure" => $nutrient->unit_of_measure,
//                                 "product_id" => $product->id
//                             ]);
//                         }
//                     }

//                     if($entry->nutri_score_facts) {
//                         ProductScore::create([
//                             "product_id" => $product->id,
//                             "fvpn_total_percentage" => $entry->nutri_score_facts->fvpn_total_percentage,
//                             "fvpn_total_percentage_estimated" => $entry->nutri_score_facts->fvpn_total_percentage_estimated,
//                             "fruit_percentage" => $entry->nutri_score_facts->fruit_percentage,
//                             "vegetable_percentage" => $entry->nutri_score_facts->vegetable_percentage,
//                             "pulses_percentage" => $entry->nutri_score_facts->pulses_percentage,
//                             "nuts_percentage" => $entry->nutri_score_facts->nuts_percentage,
//                             "fruit_percentage_dried" => $entry->nutri_score_facts->fruit_percentage_dried,
//                             "vegetable_percentage_dried" => $entry->nutri_score_facts->vegetable_percentage_dried,
//                             "pulses_percentage_dried" => $entry->nutri_score_facts->pulses_percentage_dried,
//                             "ofcom_n_energy_kj" => $entry->nutri_score_facts->ofcom_n_energy_kj,
//                             "ofcom_n_saturated_fat" => $entry->nutri_score_facts->ofcom_n_saturated_fat,
//                             "ofcom_n_sugars" => $entry->nutri_score_facts->ofcom_n_sugars,
//                             "ofcom_n_salt" => $entry->nutri_score_facts->ofcom_n_salt,
//                             "ofcom_p_protein" => $entry->nutri_score_facts->ofcom_p_protein,
//                             "ofcom_p_fvpn" => $entry->nutri_score_facts->ofcom_p_fvpn,
//                             "ofcom_p_dietary_fiber" => $entry->nutri_score_facts->ofcom_p_dietary_fiber,
//                             "ofcom_n_energy_kj_mixed" => $entry->nutri_score_facts->ofcom_n_energy_kj_mixed,
//                             "ofcom_n_saturated_fat_mixed" => $entry->nutri_score_facts->ofcom_n_saturated_fat_mixed,
//                             "ofcom_n_sugars_mixed" => $entry->nutri_score_facts->ofcom_n_sugars_mixed,
//                             "ofcom_n_salt_mixed" => $entry->nutri_score_facts->ofcom_n_salt_mixed,
//                             "ofcom_p_protein_mixed" => $entry->nutri_score_facts->ofcom_p_protein_mixed,
//                             "ofcom_p_fvpn_mixed" => $entry->nutri_score_facts->ofcom_p_fvpn_mixed,
//                             "ofcom_p_dietary_fiber_mixed" => $entry->nutri_score_facts->ofcom_p_dietary_fiber_mixed,
//                         ]);
//                     }

//                 }

//             }

//         }

//         $response = $client->request("GET", "products/worse-products/category/$category->id?marketRegion=ch&retailer=migros");

//         if($body = $response->getBody()) {

//             $result = json_decode($body);

//             if($result->success) {

//                 foreach($result->products as $entry) {

//                     $product = Product::create([
//                         "gtin" => $entry->gtin,
//                         "product_name_en" => $entry->product_name_en,
//                         "product_name_de" => $entry->product_name_de,
//                         "product_name_fr" => $entry->product_name_fr,
//                         "product_name_it" => $entry->product_name_it,
//                         "producer" => $entry->producer,
//                         "product_size" => $entry->product_size,
//                         "product_size_unit_of_measure" => $entry->product_size_unit_of_measure,
//                         "serving_size" => $entry->serving_size,
//                         "comment" => $entry->comment,
//                         "image" => $entry->image,
//                         "back_image" => $entry->back_image,
//                         "major_category_id" => $entry->major_category,
//                         "minor_category_id" => $entry->minor_category,
//                         "source" => $entry->source,
//                         "source_checked" => $entry->source_checked,
//                         "health_percentage" => $entry->health_percentage,
//                         "weighted_article" => $entry->weighted_article,
//                         "price" => $entry->price,
//                         "ofcom_value" => $entry->ofcom_value,
//                         "nutri_score_final" => $entry->nutri_score_final,
//                     ]);

//                     foreach($entry->allergens as $allergen) {
//                         AllergenProduct::create([
//                             "name" => $allergen->name,
//                             "product_id" => $product->id
//                         ]);
//                     }

//                     foreach($entry->ingredients as $ingredient) {
//                         IngredientProduct::create([
//                             "lang" => $ingredient->lang,
//                             "text" => $ingredient->text,
//                             "product_id" => $product->id
//                         ]);
//                     }

//                     foreach($entry->nutrients as $nutrient) {
//                         if($nutrient->name) {
//                             NutrientProduct::create([
//                                 "name" => $nutrient->name,
//                                 "amount" => $nutrient->amount,
//                                 "unit_of_measure" => $nutrient->unit_of_measure,
//                                 "product_id" => $product->id
//                             ]);
//                         }
//                     }

//                     if($entry->nutri_score_facts) {
//                         ProductScore::create([
//                             "product_id" => $product->id,
//                             "fvpn_total_percentage" => $entry->nutri_score_facts->fvpn_total_percentage,
//                             "fvpn_total_percentage_estimated" => $entry->nutri_score_facts->fvpn_total_percentage_estimated,
//                             "fruit_percentage" => $entry->nutri_score_facts->fruit_percentage,
//                             "vegetable_percentage" => $entry->nutri_score_facts->vegetable_percentage,
//                             "pulses_percentage" => $entry->nutri_score_facts->pulses_percentage,
//                             "nuts_percentage" => $entry->nutri_score_facts->nuts_percentage,
//                             "fruit_percentage_dried" => $entry->nutri_score_facts->fruit_percentage_dried,
//                             "vegetable_percentage_dried" => $entry->nutri_score_facts->vegetable_percentage_dried,
//                             "pulses_percentage_dried" => $entry->nutri_score_facts->pulses_percentage_dried,
//                             "ofcom_n_energy_kj" => $entry->nutri_score_facts->ofcom_n_energy_kj,
//                             "ofcom_n_saturated_fat" => $entry->nutri_score_facts->ofcom_n_saturated_fat,
//                             "ofcom_n_sugars" => $entry->nutri_score_facts->ofcom_n_sugars,
//                             "ofcom_n_salt" => $entry->nutri_score_facts->ofcom_n_salt,
//                             "ofcom_p_protein" => $entry->nutri_score_facts->ofcom_p_protein,
//                             "ofcom_p_fvpn" => $entry->nutri_score_facts->ofcom_p_fvpn,
//                             "ofcom_p_dietary_fiber" => $entry->nutri_score_facts->ofcom_p_dietary_fiber,
//                             "ofcom_n_energy_kj_mixed" => $entry->nutri_score_facts->ofcom_n_energy_kj_mixed,
//                             "ofcom_n_saturated_fat_mixed" => $entry->nutri_score_facts->ofcom_n_saturated_fat_mixed,
//                             "ofcom_n_sugars_mixed" => $entry->nutri_score_facts->ofcom_n_sugars_mixed,
//                             "ofcom_n_salt_mixed" => $entry->nutri_score_facts->ofcom_n_salt_mixed,
//                             "ofcom_p_protein_mixed" => $entry->nutri_score_facts->ofcom_p_protein_mixed,
//                             "ofcom_p_fvpn_mixed" => $entry->nutri_score_facts->ofcom_p_fvpn_mixed,
//                             "ofcom_p_dietary_fiber_mixed" => $entry->nutri_score_facts->ofcom_p_dietary_fiber_mixed,
//                         ]);
//                     }

//                 }

//             }

//         }

//     }

// });
