<?php

namespace App\Http\Controllers;

use App\Reward;
use Illuminate\Http\Request;

class RewardController extends Controller {

    public function index() {
        return Reward::all();
    }

}
