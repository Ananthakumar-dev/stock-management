<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryRequest;
use App\Models\Inventory;
use App\Services\InventoryService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
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
            ->orderBy('inventories.id', 'DESC')
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
        try {
            $validatedFields = $inventoryRequest->validated();
            $this->inventoryService->store($validatedFields);
        } catch (Exception $e) {
            return false;
        }

        Session::flash('success', 'Inventory created successfully');
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
        try {
            $validatedFields = $inventoryRequest->validated();
            $this->inventoryService->updateInventory($validatedFields, $id);
    
            Session::flash('success', 'Inventory updated successfully');
        } catch (Exception $e) {
            return false;
        }

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
        try {
            // Find the inventory record
            $inventory = Inventory::findOrFail($id);
    
            // Delete the inventory record
            $inventory->delete();
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong');
        }

        return back()->with('success', 'Inventory deleted successfully');
    }
}
