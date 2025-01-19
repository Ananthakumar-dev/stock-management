<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryRequest;
use App\Models\Inventory;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventoryController extends Controller
{
    // initiate dependencies
    public function __construct(
        private InventoryService $inventoryService
    ) {
        //
    }

    public function index(
        Request $request
    ) {
        $search = $request->query('search');

        $allInventories = $this->inventoryService
            ->getInventories()
            ->when($search, function ($query, $search) {
                $query->where('items.name', 'like', "%$search%")
                    ->orWhere('inventories.quantity', 'like', "%$search%")
                    ->orWhere('inventories.type', 'like', "%$search%")
                    ->orWhere('stores.name', 'like', "%$search%")
                    ->orWhere('users.name', 'like', "%$search%");
            })
            ->paginate(PAGINATION)
            ->withQueryString();

        return Inertia::render('Inventory/Index/Index', [
            'inventories' => $allInventories,
            'initialSearch' => $search
        ]);
    }

    public function create()
    {
        return Inertia::render('Inventory/Add/Index');
    }

    public function store(
        InventoryRequest $inventoryRequest
    ) {
        $validatedFields = $inventoryRequest->validated();
        $this->inventoryService->store($validatedFields);

        return true;
    }

    public function show(
        $id
    ) {
        $inventory = $this->inventoryService->getInventoryDetails($id);
        return Inertia::render('Inventory/Edit/Index', [
            'inventory' => $inventory
        ]);
    }

    public function update(
        InventoryRequest $inventoryRequest,
        $id
    ) {
        $validatedFields = $inventoryRequest->validated();
        $inventory = $this->inventoryService->updateInventory($validatedFields, $id);

        return true;
    }

    public function basicData()
    {
        $basicData = $this->inventoryService
            ->getBasicData();

        return $basicData;
    }

    public function itemDetails(
        $id
    ) {
        return $this->inventoryService->getItemDetails($id);
    }

    public function destroy($id)
    {
        // Find the inventory record
        $inventory = Inventory::findOrFail($id);

        // Delete the inventory record
        $inventory->delete();

        return redirect()->route('inventories.index');
    }
}
