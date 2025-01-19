<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemRequest;
use App\Models\Inventory;
use App\Models\Item;
use App\Services\ItemService;
use App\Services\MeasurementService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ItemController extends Controller
{
    public function index(
        ItemService $itemService,
        Request $request
    ) {
        $search = $request->query('search');

        $allItems = $itemService
            ->getItems()
            ->when($search, function ($query, $search) {
                $query->where('items.name', 'like', "%$search%")
                    ->orWhere('quantity', 'like', "%$search%");
            })
            ->orderBy('id', 'DESC')
            ->paginate(PAGINATION)
            ->withQueryString();

        return Inertia::render('Item/Index/Index', [
            'items' => $allItems,
            'initialSearch' => $search
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
        try {
            $validatedFields = $itemRequest->validated();
            $itemService->store($validatedFields);

            Session::flash('success', 'Item created successfully');
        } catch (Exception $e) {
            return false;
        }

        return true;
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
            'availability' => 'required|integer',
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

        try {
            $itemService->update($validatedFields, $id);
        } catch (Exception $e) {
            return false;
        }

        Session::flash('success', 'Item updated successfully');
        return true;
    }

    public function destroy($id)
    {
        try {
            $item = Item::findOrFail($id);

            $inventory = Inventory::where('item_id', $id);
            if ($inventory->count()) {
                return back()->with('error', 'Cannot delete item. Inventory is associated with this item.');
            }
            $item->delete();
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong');
        }

        return back()->with('success', 'Item deleted successfully');
    }
}
