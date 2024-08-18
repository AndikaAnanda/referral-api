<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Point;
use App\Models\Referral;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function register(Request $request) {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'referral_code' => 'nullable|string|exists:users,referral_code'
            ]);

            // Generate random referral code
            $referralCode = Str::random(10);

            $referrerId = null;
            if ($request->referral_code) {
                $referrer = User::where('referral_code', $request->referral_code)->first();
                $referrerId = $referrer->id;
            }

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'referral_code' => $referralCode,
                'referred_by' => $referrerId
            ]);

            if ($referrerId) {
                Referral::create([
                    'referrer_id' => $referrerId,
                    'referred_id' => $user->id,
                ]);
                $this->awardPoints($referrerId, 50, 'Referral');
                $this->awardPoints($user->id, 50, 'Referred by');
            }

            return response()->json([
                'message' => 'User created',
                'user' => $user
            ], 200);

        } catch(ValidationException $e) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occured while creating user',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function awardPoints ($userId, $points, $reason) {
        Point::create([
            'user_id' => $userId,
            'points' => $points,
            'reason' => $reason
        ]);
    }
}
