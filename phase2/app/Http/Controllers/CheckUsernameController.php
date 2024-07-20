<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckUsernameController extends Controller
{
    public function checkUsername(Request $request)
    {
        $username = $request->input('username');

        $exists = User::where('username', $username)->exists();

        if ($exists) {
            return response()->json([
                'exists' => true,
                'message' => 'username is taken'
            ], 200); // Username is taken
        } else {
            return response()->json([
                'exists' => false,
                'message' => 'username is available'
            ], 200); // Username is available
        }
    }
}
