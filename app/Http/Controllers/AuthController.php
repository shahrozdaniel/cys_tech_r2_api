<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class AuthController extends Controller
{
	public function register(Request $request)
	{
		try {
			$request->validate([
				'name' => 'required|string',
				'email' => 'required|string|email|unique:users',
				'password' => 'required|string|min:8',
			]);

			$user = User::create([
				'name' => $request->name,
				'email' => $request->email,
				'password' => Hash::make($request->password),
			]);

			Log::info('User registered', ['user_id' => $user->id, 'email' => $user->email]);

			return response()->json([
				'message' => 'User registered successfully',
				// 'token' => $user->createToken('API Token')->plainTextToken
			]);
		} catch (QueryException $e) {
			Log::error('Database error during registration', ['error' => $e->getMessage()]);
			return response()->json(['error' => 'Error occurred during registration. Please try again later.'], 500);
		} catch (\Exception $e) {
			Log::error('Unexpected error during registration', ['error' => $e->getMessage()]);
			return response()->json(['error' => 'Failed to register user. Please try again later.'], 500);
		}
	}

	public function login(Request $request)
	{
		try {
			$request->validate([
				'email' => 'required|email',
				'password' => 'required',
			]);

			$user = User::where('email', $request->email)->first();

			if (!$user || !Hash::check($request->password, $user->password)) {
				Log::warning('Failed login attempt', ['email' => $request->email]);
				throw ValidationException::withMessages([
					'email' => ['The provided credentials are incorrect.'],
				]);
			}

			Log::info('User logged in', ['user_id' => $user->id, 'email' => $user->email]);

			return response()->json([
				'message' => 'Login successful',
				'token' => $user->createToken('API Token')->plainTextToken,
			]);
		} catch (ValidationException $e) {
			Log::error('Validation error during login', ['error' => $e->getMessage()]);
			return response()->json(['error' => 'Invalid credentials provided.'], 401);
		} catch (\Exception $e) {
			Log::error('Unexpected error during login', ['error' => $e->getMessage()]);
			return response()->json(['error' => 'Failed to log in. Please try again later.'], 500);
		}
	}
}
