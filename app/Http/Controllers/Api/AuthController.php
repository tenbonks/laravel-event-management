<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Log in a user.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        // If no user, throw validation error
        if (!$user) {
            throw $this->emailValidationException();
        }

        // If no the hashed request, and user password don't match, throw validation error
        if (!Hash::check($request->password, $user->password)) {
            throw $this->emailValidationException();
        }

        // At this point, we have authenticated the users credentials
        $token = $user->createToken('api-token')->plainTextToken;

        // At this point, we have generated a token for the user in the db
        return response()->json([
            'token' => $token,
        ]);
    }

    /**
     * Log out a user.
     */
    public function logout(Request $request)
    {
        //
    }

    private function emailValidationException(): ValidationException
    {
        return ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }
}
