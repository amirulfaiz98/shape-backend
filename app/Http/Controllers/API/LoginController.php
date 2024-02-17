<?php

namespace App\Http\Controllers\API;


use Laravel\Passport\Passport;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        try{
            if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

                return response()->json(['error' => 'Wrong Email or Password'], 500);
            }

            $user = Auth::user();

            if ($request->remember_me == 'true') {
                Passport::personalAccessTokensExpireIn(now()->addWeek());
            }

            $token = $user->createToken('token')->accessToken;

            $user->token = $token;

            return response()->json($user, 200);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }

    }
}
