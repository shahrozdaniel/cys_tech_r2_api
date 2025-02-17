<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TransactionController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth:sanctum');  // Ensure user is authenticated for all actions
	}

	/**
	 * Create a new transaction.
	 */
	public function create(Request $request)
	{
		try {
			$request->validate([
				'title' => 'required|string|max:255',
				'description' => 'required|string',
			]);

			$transaction = new Transaction();
			$transaction->user_id = Auth::id();
			$transaction->title = $request->title;
			$transaction->description = $request->description;
			$transaction->save();
			
			Log::info('Transaction created by user ID ' . Auth::id(), ['transaction_id' => $transaction->id]);

			return response()->json($transaction, 201);
		} catch (\Exception $e) {
			Log::error('Error creating transaction', ['error' => $e->getMessage()]);
			return response()->json(['error' => 'Failed to create transaction. Please try again later.'], 500);
		}
	}
	
	public function index()
	{
		try {
			$transactions = Auth::user()->transactions;

			if ($transactions->isEmpty()) {
				return response()->json(['message' => 'No transactions found for this user.'], 404);
			}

			return response()->json($transactions);
		} catch (\Exception $e) {
			Log::error('Error retrieving transactions', ['error' => $e->getMessage()]);
			return response()->json(['error' => 'Failed to retrieve transactions. Please try again later.'], 500);
		}
	}
	
	public function show($id)
	{
		try {
			$transaction = Transaction::findOrFail($id);

			// Authorization: Only allow the user to access their own transaction
			$this->authorize('view', $transaction);

			return response()->json($transaction);
		} catch (ModelNotFoundException $e) {
			Log::warning('Transaction not found', ['transaction_id' => $id, 'user_id' => Auth::id()]);
			return response()->json(['error' => 'Transaction not found.'], 404);
		} catch (\Exception $e) {
			Log::error('Error retrieving transaction', ['error' => $e->getMessage()]);
			return response()->json(['error' => 'Failed to retrieve transaction. Please try again later.'], 500);
		}
	}
}
