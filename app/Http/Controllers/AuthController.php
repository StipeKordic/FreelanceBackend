<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;
class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validated = $request->validate([
            'email'=>'required|email',
            'password'=> 'required|confirmed',
            'first_name'=>'required|string',
            'last_name'=>'required|string'
        ]);

        return User::create(
            [
                'email'=>$request->email,
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
                'password'=>Hash::make($request->password)

            ]
        );
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('pziToken')->plainTextToken;

        return $token;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer'
        ]);
    }
    public function user(Request $request)
    {
        return Auth::user();
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response('Success');
    }
}
