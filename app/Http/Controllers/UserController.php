<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\User;
use App\Product;
use App\Replacement;
use Illuminate\Http\Request;

class UserController extends Controller {

    public function show() {

        $bills = User::with(["bills"])->where("id", Auth::id())->first()->bills;
        $points = User::with(["bills"])->where("id", Auth::id())->first()->bills;

        $new_bills = collect();
        $old_bills = collect();

        $values = [
            "A" => 0,
            "B" => 7,
            "C" => 14,
            "D" => 21,
            "E" => 28
        ];


        $bills->each(function($bill) use ($values, &$new_bills, &$old_bills) {

            $new_nutri_score = $this->getClosest(collect($bill->products)->transform(function($item) use ($values) {
                return $values[$item->product->nutri_score_final];
            })->avg(), $values);

            $old_products_id = $bill->products->pluck("product_id")->merge($bill->replacements->pluck("product_a_id"))->diff($bill->replacements->pluck("product_b_id"))->values();

            $old_products = Product::whereIn("id", $old_products_id)->get();

            $old_nutri_score = $this->getClosest(collect($old_products)->transform(function($item) use ($values) {
                return $values[$item->nutri_score_final];
            })->avg(), $values);

            $previous_bill = [
                "price" => collect($old_products)->transform(function($item) {
                    return $item->price;
                })->sum(),
                "co2_level" => collect($old_products)->transform(function($item) use ($values) {
                    return $values[$item->nutri_score_final];
                })->sum(),
                "nutri_score" => array_search($old_nutri_score, $values)
            ];

            $current_bill = [
                "price" => collect($bill->products)->transform(function($item) {
                    return $item->product->price;
                })->sum(),
                "co2_level" => collect($bill->products)->transform(function($item) use ($values) {
                    return $values[$item->product->nutri_score_final];
                })->sum(),
                "nutri_score" => array_search($new_nutri_score, $values)
            ];

            $old_bills->push($previous_bill);
            $new_bills->push($current_bill);

        });

        $old_co2 = collect($old_bills)->transform(function($item) {
            return $item["co2_level"];
        })->sum();

        $new_co2 = collect($new_bills)->transform(function($item) {
            return $item["co2_level"];
        })->sum();

        $co2_saved = $old_co2 - $new_co2;

        $money_saved = collect($old_bills)->transform(function($item) {
            return $item["price"];
        })->sum() - collect($new_bills)->transform(function($item) {
            return $item["price"];
        })->sum();

        return [
            "user" => Auth::user(),
            "insights" => [
                "co2_saved_level" => $co2_saved > 0 ? $co2_saved : null,
                "co2_saved_percentage" => $old_co2 > $new_co2 ? $co2_saved * 100 / $old_co2 : null,
                "money_saved" => $money_saved > 0 ? $money_saved : null,
                "points" => $points->transform(function($item) {
                    return $item->nutri_score;
                })->sum(),
                "stats" => $bills->sortByDesc("id")->take(5)->transform(function($item) {
                    return $item->nutri_score;
                })->reverse()->values()
            ]
        ];
    }

    public function update(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required|confirmed|min:6',
            'birthday' => 'required',
            'weight' => 'required',
            'height' => 'required',
            'number_of_meals' => 'required',
            'goal' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'data' => null
            ], 400);
        }

        $user = Auth::user();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->birthday = $request->birthday;
        $user->weight = $request->weight;
        $user->height = $request->height;
        $user->number_of_meals = $request->number_of_meals;
        $user->goal = $request->goal;

        if($user->save()) {

            return response()->json([
                'status' => 'success',
                'message' => 'Profilo aggiornato con successo',
                'data' => null
            ], 200);

        }

        return response()->json([
            'status' => 'error',
            'message' => 'Si è verificato un problema, riprova tra poco',
            'data' => null
        ], 400);

    }

    static function getClosest($search, $arr) {
        $closest = null;
        foreach ($arr as $item) {
            if ($closest === null || abs($search - $closest) > abs($item - $search)) {
                $closest = $item;
            }
        }
        return $closest;
    }

}
