<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model {

    public $timestamps = false;

    protected $fillable = ["id", "name"];

}
