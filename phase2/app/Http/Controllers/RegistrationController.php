<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RegistrationController extends Controller
{
    private function validateData(Request $request): \Illuminate\Validation\Validator
    {
        return Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'mobile_number' => 'required|numeric|regex:/^\+?\d{8,15}$/|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'full_name' => 'required|min:3|string|max:255',
            'date_of_birth' => 'required|date',
            'address' => 'required|string|max:255',
            'user_image' => 'required|image',
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[0-9])(?=.*[!@#$%^&*(),.?":{}|<>]).{8,}$/'],
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        try {
            $validator = $this->validateData($request);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()->getMessages(),
                ], 422);
            }

            DB::beginTransaction();

            if ($request->hasFile('user_image') && $request->file('user_image')->isValid()) {
                $user_image_path = $request->user_image->store('public/user_images');
            }

            $user_data = $request->only([
                'full_name', 'username', 'email', 'mobile_number', 'date_of_birth', 'address'
            ]);
            $user_data['password'] = bcrypt($request->password);
            $user_data['user_image'] = $user_image_path ?? null;

            $user = User::create($user_data);

            if ($user) {
                DB::commit();

                Mail::to(env('ADMIN_MAIL'))->send(new WelcomeEmail($user));

                return response()->json([
                    'status' => 'success',
                    'message' => 'User registered successfully. Welcome email sent!',
                    'user' => $user,
                ], 201);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Internal Server Error!'
                ], 500);
            }


        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($user_image_path)) {
                Storage::delete($user_image_path);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

}
