<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MusicController extends Controller
{
    public function music(Request $request) {
        try {
            $redirect_uri = env('REDIRECT_URI');
            $client_id = env('CLIENT_ID');
            $client_secret = env('CLIENT_SECRET');
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($client_id . ':' . $client_secret)
            ])->asForm()->post('https://accounts.spotify.com/api/token', [
                'code' => $request->code,
                'redirect_uri' => $redirect_uri,
                'grant_type' => 'authorization_code'
            ]);
            if($response->failed()) {
                return response()->json([
                    'data' => $response->json()
                ], 500);
            }
            return response()->json([
                'data' => $response->json()
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'message' => $error
            ], 500);
        }
    }
    public function refresh(Request $request) {
        try{
            $refreshToken = $request->refreshToken;
            $client_id = env('CLIENT_ID');
            $client_secret = env('CLIENT_SECRET');
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($client_id . ':' . $client_secret)
            ])->asForm()->post('https://accounts.spotify.com/api/token', [
                'refresh_token' => $refreshToken,
                'grant_type' => 'refresh_token'
            ]);
            if($response->failed()) {
                return response()->json([
                    'data' => $response->json()
                ], 500);
            }
            return response()->json([
                'data' => $response->json()
            ], 200);
        } catch (Exception $error) {
            return response()->json([
                'message' => $error
            ], 500);
        }
    }
}
