<?php

namespace App\Http\Controllers;

use App\Models\User;
use JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|unique:users',
            'email'    => 'required|unique:users',
            'password' => 'required|min:8'
        ]);

        return User::create([
            'username' => $request->json('username'),
            'email'    => $request->json('email'),
            'password' => Hash::make($request->json(['password']))
        ]);
    }

    public function signin(Request $request)
    {
        $this->validate($request, [
            'username' => 'required',
            'password' => 'required|min:8'
        ]);

        // grab credentials from the request
        // $credentials = $request->only('username', 'password');
        if ($request->has(['username', 'email', 'password'])) {
            $credentials = $request->only('email', 'password');
        } // Request input contains username and password
        elseif ($request->has(['username', 'password'])) {
            $credentials = $request->only('username', 'password');
        } else {
            $credentials = $request->only('email', 'password');
        }
            try {
                // attempt to verify the credentials and create a token for the user
                if (! $token = JWTAuth::attempt($credentials)) {
                    return response()->json(['error' => 'invalid_credentials'], 401);
                }
            } catch (JWTException $e) {
                // something went wrong whilst attempting to encode the token
                return response()->json(['error' => 'could_not_create_token'], 500);
            }   

        // all good so return the token
        return response()->json([
            'user_id'  => $request->user()->id,
            'username' => $request->user()->username,
            'token'    => $token
        ]);
    }
}
