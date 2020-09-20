<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("gtin");
            $table->string("product_name_en");
            $table->string("product_name_de");
            $table->string("product_name_fr");
            $table->string("product_name_it");
            $table->string("producer")->nullable();
            $table->string("product_size")->nullable();
            $table->string("product_size_unit_of_measure")->nullable();
            $table->string("serving_size")->nullable();
            $table->text("comment")->nullable();
            $table->string("image")->nullable();
            $table->string("back_image")->nullable();
            $table->unsignedBigInteger("major_category_id");
            $table->unsignedBigInteger("minor_category_id");
            $table->string("source")->nullable();
            $table->boolean("source_checked");
            $table->decimal("health_percentage")->nullable();
            $table->boolean("weighted_article");
            $table->decimal("price");
            $table->integer("ofcom_value");
            $table->enum("nutri_score_final", ["A", "B", "C", "D", "E"]);
            $table->timestamps();
        });

        Schema::create('allergen_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("product_id");
            $table->string("name");
        });

        Schema::create('ingredient_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("product_id");
            $table->enum("lang", ["de", "en", "it", "fr"]);
            $table->text("text");
        });

        Schema::create('nutrient_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("product_id");
            $table->string("name");
            $table->decimal("amount")->default(0.0)->nullable();
            $table->string("unit_of_measure")->nullable();
        });

        Schema::create('product_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("product_id");
            $table->decimal("fvpn_total_percentage")->default(0.0)->nullable();
            $table->decimal("fvpn_total_percentage_estimated")->default(0.0)->nullable();
            $table->decimal("fruit_percentage")->default(0.0)->nullable();
            $table->decimal("vegetable_percentage")->default(0.0)->nullable();
            $table->decimal("pulses_percentage")->default(0.0)->nullable();
            $table->decimal("nuts_percentage")->default(0.0)->nullable();
            $table->decimal("fruit_percentage_dried")->default(0.0)->nullable();
            $table->decimal("vegetable_percentage_dried")->default(0.0)->nullable();
            $table->decimal("pulses_percentage_dried")->default(0.0)->nullable();
            $table->decimal("ofcom_n_energy_kj")->default(0.0)->nullable();
            $table->decimal("ofcom_n_saturated_fat")->default(0.0)->nullable();
            $table->decimal("ofcom_n_sugars")->default(0.0)->nullable();
            $table->decimal("ofcom_n_salt")->default(0.0)->nullable();
            $table->decimal("ofcom_p_protein")->default(0.0)->nullable();
            $table->decimal("ofcom_p_fvpn")->default(0.0)->nullable();
            $table->decimal("ofcom_p_dietary_fiber")->default(0.0)->nullable();
            $table->decimal("ofcom_n_energy_kj_mixed")->default(null)->nullable();
            $table->decimal("ofcom_n_saturated_fat_mixed")->default(null)->nullable();
            $table->decimal("ofcom_n_sugars_mixed")->default(null)->nullable();
            $table->decimal("ofcom_n_salt_mixed")->default(null)->nullable();
            $table->decimal("ofcom_p_protein_mixed")->default(null)->nullable();
            $table->decimal("ofcom_p_fvpn_mixed")->default(null)->nullable();
            $table->decimal("ofcom_p_dietary_fiber_mixed")->default(null)->nullable();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreign("major_category_id")->references("id")->on("major_categories")->onDelete('cascade');
            $table->foreign("minor_category_id")->references("id")->on("minor_categories")->onDelete('cascade');
        });

        Schema::table('allergen_products', function (Blueprint $table) {
            $table->foreign("product_id")->references("id")->on("products")->onDelete('cascade');
        });

        Schema::table('ingredient_products', function (Blueprint $table) {
            $table->foreign("product_id")->references("id")->on("products")->onDelete('cascade');
        });

        Schema::table('nutrient_products', function (Blueprint $table) {
            $table->foreign("product_id")->references("id")->on("products")->onDelete('cascade');
        });

        Schema::table('product_scores', function (Blueprint $table) {
            $table->foreign("product_id")->references("id")->on("products")->onDelete('cascade');
        });

    }

}
