<?php

namespace App\Http\Controllers;

use Mail;
use Auth;
use JWTAuth;
use Validator;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller {

    public function login(Request $request) {

        $credentials = $request->only('email', 'password');

        if(!$request->email || !$request->password) {
            return response()->json([
                'status' => 'error',
                'message' => 'Credenziali non valide',
                'data' => null
            ], 406);
        }

        $user = User::where('email', $request->email)->first();

        if(!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Utente non registrato',
                'data' => null
            ], 404);
        }

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'E-mail e/o password errate',
                'data' => null
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Login effettuato con successo',
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
            ]
        ], 200);

    }

    public function guard() {
        return Auth::guard('api');
    }

    public function logout(Request $request) {

        $this->guard()->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout effettuato con successo',
            'data' => null
        ], 200);

    }

    public function forgot(Request $request) {

		$validator = Validator::make($request->all(), [
			'email' => 'required|email|exists:users,email',
		]);

		if ($validator->fails()) {
			return response()->json([
				'status' => 'error',
				'message' => 'Si è verificato un problema con l\'indirizzo e-mail inserito',
				'data' => $validator->errors()
			], 400);
		}

        $response = $this->broker()->sendResetLink($request->only('email'));

		if($response == Password::RESET_LINK_SENT) {
			return response()->json([
				'status' => 'success',
				'message' => 'Un link di reset è stato recapitato alla tua casella e-mail.',
				'data' => null
			], 200);
		}

		return response()->json([
			'status' => 'error',
			'message' => 'Si è verificato un problema con l\'indirizzo e-mail inserito',
			'data' => $response
		], 400);

    }

	public function broker() {
		return Password::broker();
    }

    public function register(Request $request) {

        $user = User::where('email', $request->email)->first();

        if($user) {

            return response()->json([
                'status' => 'error',
                'message' => 'Siamo spiacenti, esiste già un account registrato con questo indirizzo e-mail',
                'data' => null
            ], 400);

        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'birthday' => $request->birthday,
            'weight' => $request->weight,
            'height' => $request->height,
            'number_of_meals' => $request->number_of_meals,
            'goal' => $request->goal
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Account registrato con successo',
            'data' => [
                'access_token' => JWTAuth::fromUser($user),
                'token_type' => 'Bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
                'user' => $user
            ]
        ], 200);
    }

}
