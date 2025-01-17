<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryRequest;
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

    public function index()
    {
        $allInventories = $this->inventoryService
            ->getInventories()
            ->paginate(PAGINATION);

        return Inertia::render('Inventory/Index/Index', [
            'inventories' => $allInventories
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
}
