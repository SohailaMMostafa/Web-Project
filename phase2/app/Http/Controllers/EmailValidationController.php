<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class EmailValidationController extends Controller
{
    public function checkEmailExistence(Request $request)
    {
        $email = $request->input('email');
        $exists = User::where('email', $email)->exists();


        if ($exists) {
            return response()->json([
                'exists' => true,
                'message' => 'Email already exists'
            ], 200);
        } else {
            return response()->json([
                'exists' => false,
                'message' => 'Email is available'
            ], 200);
        }
    }
}
