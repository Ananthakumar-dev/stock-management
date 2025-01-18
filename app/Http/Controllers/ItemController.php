<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemRequest;
use App\Models\Item;
use App\Services\ItemService;
use App\Services\MeasurementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ItemController extends Controller
{
    public function index(
        ItemService $itemService
    ) {
        $allItems = $itemService
            ->getItems()
            ->paginate(PAGINATION);

        return Inertia::render('Item/Index/Index', [
            'items' => $allItems
        ]);
    }

    public function create(
        MeasurementService $measurementService
    ) {
        $measurements = $measurementService
            ->getMeasurements()
            ->get();

        return Inertia::render('Item/Add/Index', [
            'measurements' => $measurements
        ]);
    }

    public function store(
        ItemRequest $itemRequest,
        ItemService $itemService
    ) {
        $validatedFields = $itemRequest->validated();
        $itemService->store($validatedFields);

        return redirect()->route('items.index');
    }

    public function show(
        MeasurementService $measurementService,
        int $id
    ) {
        $item = Item::with(['item_attributes'])->findOrFail($id);

        $measurements = $measurementService
            ->getMeasurements()
            ->get();

        return Inertia::render('Item/Edit/Index', [
            'item' => $item,
            'measurements' => $measurements
        ]);
    }

    public function update(
        Request $request,
        ItemService $itemService,
        $id
    ) {
        $validatedFields = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'measurement_id' => 'required|exists:measurements,id',
            'attributes' => 'nullable|array',
            'attributes.*.attribute_id' => [
                'required',
                function ($attribute, $value, $fail) use ($id, $request) {
                    $index = explode('.', $attribute)[1]; // Get the index from 'attributes.{index}.attribute_id'
                    $attributeId = $request->input("attributes.$index.id");

                    $exists = DB::table('item_attributes')
                        ->where('item_id', $id)
                        ->where('attribute_id', $value)
                        ->when($attributeId, function ($query) use ($attributeId) {
                            return $query->where('id', '!=', $attributeId);
                        })
                        ->exists();

                    if ($exists) {
                        $fail("The selected attribute is already assigned to this item.");
                    }
                },
            ],
            'attributes.*.value' => 'required|string|max:255',
        ]);

        $itemService->update($validatedFields, $id);

        return true;
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return redirect()->route('items.index');
    }
}
