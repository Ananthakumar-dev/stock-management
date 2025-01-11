<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function index()
    {
        return Attribute::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:Attributes,name|max:255',
        ]);

        $Attribute = Attribute::create(['name' => $request->name]);

        return response()->json(['message' => 'Attribute added successfully', 'data' => $Attribute], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:Attributes,name,' . $id . '|max:255',
        ]);

        $Attribute = Attribute::findOrFail($id);
        $Attribute->update(['name' => $request->name]);

        return response()->json(['message' => 'Attribute updated successfully', 'data' => $Attribute]);
    }

    public function destroy($id)
    {
        $Attribute = Attribute::findOrFail($id);
        $Attribute->delete();

        return response()->json(['message' => 'Attribute deleted successfully']);
    }
}
