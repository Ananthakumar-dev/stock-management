<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        return Store::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'address' => 'required',
            'phone' => 'nullable|digits:10',
        ]);

        $store = Store::create($request->all());

        return response()->json(['message' => 'Store added successfully', 'data' => $store], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'address' => 'required',
            'phone' => 'nullable|digits:10',
        ]);

        $store = Store::findOrFail($id);
        $store->update($request->all());

        return response()->json(['message' => 'Store updated successfully', 'data' => $store]);
    }

    public function destroy($id)
    {
        $store = Store::findOrFail($id);
        $store->delete();

        return response()->json(['message' => 'Store deleted successfully']);
    }
}
