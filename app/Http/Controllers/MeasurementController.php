<?php

namespace App\Http\Controllers;

use App\Models\Measurement;
use Illuminate\Http\Request;

class MeasurementController extends Controller
{
    public function index()
    {
        return Measurement::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:measurements,name|max:255',
        ]);

        $measurement = Measurement::create(['name' => $request->name]);

        return response()->json(['message' => 'Measurement added successfully', 'data' => $measurement], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:measurements,name,' . $id . '|max:255',
        ]);

        $measurement = Measurement::findOrFail($id);
        $measurement->update(['name' => $request->name]);

        return response()->json(['message' => 'Measurement updated successfully', 'data' => $measurement]);
    }

    public function destroy($id)
    {
        $measurement = Measurement::findOrFail($id);
        $measurement->delete();

        return response()->json(['message' => 'Measurement deleted successfully']);
    }
}
