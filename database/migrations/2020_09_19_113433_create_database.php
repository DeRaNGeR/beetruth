<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create("major_categories", function (Blueprint $table) {
            $table->id();
            $table->string("name_de");
            $table->string("name_en");
            $table->string("name_it");
            $table->string("name_fr");
        });

        Schema::create("minor_categories", function (Blueprint $table) {
            $table->id();
            $table->string("name_de");
            $table->string("name_en");
            $table->string("name_it");
            $table->string("name_fr");
            $table->string("nutri_score_category");
            $table->unsignedBigInteger("major_category_id");
        });

        Schema::table("minor_categories", function (Blueprint $table) {
            $table->foreign("major_category_id")->references("id")->on("major_categories")->onDelete('cascade');
        });

    }

}
