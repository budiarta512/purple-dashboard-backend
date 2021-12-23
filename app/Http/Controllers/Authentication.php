<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class Authentication extends Controller
{
    public function register(Request $request) {
        try {
            $rule = [
                'name' => 'required',
                'telp' => 'required|unique:users|max:15',
                'email' => 'required|email|unique:users',
                'password' => 'required'
            ];
            $validator = Validator::make($request->all(), $rule);
            if($validator->fails()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => $validator->errors()
                ], 400);
            }
            User::create([
                'name' => $request->name,
                'telp' => $request->telp,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil melakukan register',
            ], 201);
        } catch(Exception $error) {
            return response()->json([
                'status' => 'fail',
                'message' => $error
            ], 500);
        }
    }
    public function login(Request $request) {
        try {
            $rule = [
                'email' => 'required|email',
                'password' => 'required|min:3'
            ];
            $validator = Validator::make($request->all(), $rule);
            if($validator->fails()) {
                return response()->json([
                    'status' => 'fail',
                    'message' => $validator->errors()
                ],400);
            }
            if(!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Email atau password salah'
                ], 400);
            }
            $user = User::where('email', $request->email)->first();
            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil melakukan login',
                'data' => [
                    'user' => $user,
                    'token' => $user->createToken('token')->plainTextToken
                ]
            ], 200);
        } catch(Exception $error) {
            return response()->json([
                'status' => 'fail',
                'message' => $error
            ], 500);
        }
    }
    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil melakukan logout',
        ]);
    }
}
