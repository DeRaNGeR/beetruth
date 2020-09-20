<?php

namespace App\Http\Controllers;

use Auth;
use Validator;
use App\Bill;
use App\Product;
use App\BillProduct;
use App\Replacement;
use Illuminate\Http\Request;

class FoodController extends Controller {

    public function index() {
        return [
            "for_diet" => Product::with(["allergens", "ingredients", "nutrients", "scores"])->whereNotNull("health_percentage")->where("health_percentage", ">=", "50")->inRandomOrder()->limit(8)->get(),
            "low_co2_impact" => Product::with(["allergens", "ingredients", "nutrients", "scores"])->whereIn("nutri_score_final", ["A", "B"])->orderBy("nutri_score_final")->limit(8)->get(),
            "healthy_food" => Product::with(["allergens", "ingredients", "nutrients", "scores"])->whereIn("major_category_id", [4, 6, 10, 11])->inRandomOrder()->limit(8)->get()
        ];
    }

    public function show(Product $product_a) {
        return [
            "product_a" => Product::with(["allergens", "ingredients", "nutrients", "scores"])->where("id", $product_a->id)->first(),
            "product_b" => Product::with(["allergens", "ingredients", "nutrients", "scores"])->where("major_category_id", $product_a->major_category_id)->inRandomOrder()->first()
        ];
    }

    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'products' => 'required|array|min:1',
            'products.*' => 'required',
            'replacements' => 'array',
            'replacements.*.product_a_id' => 'required|exists:products,id',
            'replacements.*.product_b_id' => 'required_with:replacements.*.product_a_id|exists:products,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'data' => null
            ], 400);
        }

        $bill = new Bill;

        $bill->user_id = Auth::id();

        $products = Product::whereIn("id", $request->products);

        $values = [
            "A" => 0,
            "B" => 7,
            "C" => 14,
            "D" => 21,
            "E" => 28
        ];

        $bill->nutri_score_final = array_search($this->getClosest($products->pluck("nutri_score_final")->transform(function($item) use ($values) {
            return $values[$item];
        })->avg(), $values), $values, true);

        if($bill->nutri_score_final == "A") {
            $bill->nutri_score = 50;
        } else if($bill->nutri_score_final == "B") {
            $bill->nutri_score = 20;
        } else if($bill->nutri_score_final == "C") {
            $bill->nutri_score = 5;
        } else {
            $bill->nutri_score = 0;
        }

        if($bill->save()) {

            $products->each(function($item) use ($bill) {
                $product = new BillProduct;
                $product->bill_id = $bill->id;
                $product->product_id = $item->id;
                $product->save();
            });

            collect($request->replacements)->each(function($item) use ($bill) {
                $replacement = new Replacement;
                $replacement->bill_id = $bill->id;
                $replacement->product_a_id = $item["product_a_id"];
                $replacement->product_b_id = $item["product_b_id"];
                $replacement->save();
            });

            $values = [
                "A" => 0,
                "B" => 7,
                "C" => 14,
                "D" => 21,
                "E" => 28
            ];

            $bill = $bill->fresh(["products.product", "replacements.product_a", "replacements.product_b"]);

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

            $money_saved = $previous_bill["price"] - $current_bill["price"];

            $old_co2 = $previous_bill["co2_level"];

            $new_co2 = $current_bill["co2_level"];

            $co2_saved = $old_co2 - $new_co2;

            return response()->json([
                'status' => 'success',
                'message' => 'Ordinazione effettuata con successo',
                'data' => [
                    "previous_bill" => $previous_bill,
                    "current_bill" => $current_bill,
                    "co2_saved_level" => $co2_saved > 0 ? $co2_saved : null,
                    "co2_saved_percentage" => $old_co2 > $new_co2 ? $co2_saved * 100 / $old_co2 : null,
                    "money_saved" => $money_saved > 0 ? $money_saved : null
                ]
            ], 200);

        }

        return response()->json([
            'status' => 'error',
            'message' => 'Si è verificato un errore, riprova tra poco',
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
