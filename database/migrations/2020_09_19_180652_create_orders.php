<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("bills", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->integer("nutri_score");
            $table->string("nutri_score_final");
            $table->timestamps();
        });

        Schema::create("replacements", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("bill_id");
            $table->unsignedBigInteger("product_a_id");
            $table->unsignedBigInteger("product_b_id");
        });

        Schema::create("bill_products", function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("bill_id");
            $table->unsignedBigInteger("product_id");
        });

        Schema::table("bills", function (Blueprint $table) {
            $table->foreign("user_id")->references("id")->on("users")->onDelete('cascade');
        });

        Schema::table("replacements", function (Blueprint $table) {
            $table->foreign("bill_id")->references("id")->on("bills")->onDelete('cascade');
            $table->foreign("product_a_id")->references("id")->on("products")->onDelete('cascade');
            $table->foreign("product_b_id")->references("id")->on("products")->onDelete('cascade');
        });

        Schema::table("bill_products", function (Blueprint $table) {
            $table->foreign("bill_id")->references("id")->on("bills")->onDelete('cascade');
            $table->foreign("product_id")->references("id")->on("products")->onDelete('cascade');
        });

    }

}
