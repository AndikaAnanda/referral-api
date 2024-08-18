<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function authenticate (Request $request) {
        try {

            $credentials = $request->validate([
                'email' => 'required|email|exists:users',
                'password' => 'required|string|min:8'
            ]);
    
            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'message' => 'Invalid credentials',
                ], 401);
            }
    
            $request->session()->regenerate();
            $token = $request->session()->token();
            return response()->json([
                'message' => 'User created',
                'token' => $token
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'error' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Validation error',
                'error' => $e->getMessage()
            ], 422);
        }
        
    }
}
