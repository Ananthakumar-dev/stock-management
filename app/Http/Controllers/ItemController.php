<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemRequest;
use App\Models\Item;
use App\Services\ItemService;
use App\Services\MeasurementService;
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
        ItemRequest $itemRequest,
        ItemService $itemService,
        $id
    ) {
        $validatedFields = $itemRequest->validated();
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
