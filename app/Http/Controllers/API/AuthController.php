<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;

class AuthController extends Controller
{
    /**
     * Register user
     *
     */
    public function register(Request $request)
    {
        //Validate the input
        $request->validate([
            'name' => 'required|string',
            'nicename' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);

        //Create new user instance
        $user = new User([
            'name' => $request->name,
            'nicename' => $request->nicename,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        //Save the instance
        $user->save();

        //Send response back
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }

    /**
     * Login user and create token
     *
     */
    public function login(Request $request)
    {
        //Validate the input
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        //Get the credentials from the request
        $credentials = request(['email', 'password']);

        //Login
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();

        //Get token
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        
        //Send response back
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     */
    public function logout(Request $request)
    {
        //Revoke the user token
        $request->user()->token()->revoke();

        //Send response back
        return response()->json([
            'message' => 'You have been successfully logged out'
        ]);
    }
  
}
