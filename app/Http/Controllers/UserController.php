<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'designation' => 'required|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|digits:10',
            'status' => 'required|in:active,inactive',
        ]);

        $user = User::create($request->all());

        return response()->json(['message' => 'User added successfully', 'data' => $user], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'designation' => 'required|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|digits:10',
            'status' => 'required|in:active,inactive',
        ]);

        $user = User::findOrFail($id);
        $user->update($request->all());

        return response()->json(['message' => 'User updated successfully', 'data' => $user]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
