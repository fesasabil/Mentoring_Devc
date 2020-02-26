<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|unique:users',
            'email'    => 'required|unique:users|email',
            'password' => 'required|min:8'
        ]);

        // if ($this->fails()) {
        //     return response()->json($this->errors()->toJson(), 400);
        // }

        $user = User::create([
            'username' => $request->json('username'),
            'email'    => $request->json('email'),
            'password' => Hash::make($request->json(['password']))
        ]);

        $token = JWTAuth::fromUser($user);
        return response()->json(compact('user', 'token'), 201);
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

    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user not found'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token expired'], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response()->json(['token invalid'], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['token absent'], $e->getStatusCode());
        }
        return response()->json(compact('user'));
    }
}
