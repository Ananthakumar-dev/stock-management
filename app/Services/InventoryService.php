<?php

namespace App\Services;

use App\Enums\InventoryType;
use App\Models\Inventory;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * get all Inventories
     */
    public function getInventories()
    {
        $inventories = DB::table('inventories')
            ->select([
                'inventories.id',
                'inventories.quantity',
                'inventories.type',
                'items.name as item_name',
                'stores.name as store_name',
                'users.name as user_name',
            ])
            ->join('items', function ($join) {
                $join->on('items.id', '=', 'inventories.item_id');
            })
            ->join('stores', function ($join) {
                $join->on('stores.id', '=', 'inventories.store_id');
            })
            ->join('users', function ($join) {
                $join->on('users.id', '=', 'inventories.user_id');
            });

        return $inventories;
    }

    /**
     * get all basic data
     */
    public function getBasicData()
    {
        // users
        $users = DB::table('users')
            ->select([
                'id',
                DB::raw("CONCAT(name, ' ', id) AS name")
            ])
            ->get();

        // stores
        $stores = DB::table('stores')
            ->select([
                'id',
                DB::raw("CONCAT(name, ' ', id) AS name")
            ])
            ->get();

        // items
        $items = DB::table('items')
            ->select([
                'id',
                DB::raw("CONCAT(name, ' ', id) AS name")
            ])
            ->get();

        // types
        $types = InventoryType::cases();

        $return = [
            'users' => $users,
            'stores' => $stores,
            'items' => $items,
            'types' => $types,
        ];

        return $return;
    }

    /**
     * get item details
     */
    public function getItemDetails(
        $itemId
    ) {
        $item = Item::with([
            'measurement',
            'item_attributes',
            'item_attributes.attribute',
        ])
            ->where(['id' => $itemId])
            ->first();

        return $item;
    }

    /**
     * store inventory
     */
    public function store(
        $validatedFields
    ) {
        $itemId = $validatedFields['item_id'];
        $quantity = $validatedFields['quantity'];
        $type = $validatedFields['type'];

        // Validate the 'Out' case
        if ($type === 'Out') {
            $currentQuantity = DB::table('items')->where('id', $itemId)->value('quantity');
            if ($currentQuantity < $quantity) {
                return response()->json(['error' => 'Insufficient stock'], 422);
            }
        }

        // Update quantity based on type
        DB::table('items')->where('id', $itemId)->update([
            'quantity' => DB::raw("quantity " . ($type === 'In' ? '+' : '-') . " $quantity")
        ]);

        Inventory::create($validatedFields);

        return true;
    }

    /**
     * update inventory
     */
    public function updateInventory(
        $validatedFields,
        $inventoryId
    ) {
        $itemId = $validatedFields['item_id'];
        $quantity = $validatedFields['quantity'];
        $type = $validatedFields['type'];

        // Validate the 'Out' case
        if ($type === 'Out') {
            $currentQuantity = DB::table('items')->where('id', $itemId)->value('quantity');
            if ($currentQuantity < $quantity) {
                return response()->json(['error' => 'Insufficient stock'], 422);
            }
        }

        // Update quantity based on type
        DB::table('items')->where('id', $itemId)->update([
            'quantity' => DB::raw("quantity " . ($type === 'In' ? '+' : '-') . " $quantity")
        ]);

        Inventory::where([ 'id' => $inventoryId ])->update($validatedFields);

        return true;
    }

    public function getInventoryDetails(
        $inventoryId
    ) {
        return Inventory::with(['item', 'item.measurement', 'item.item_attributes', 'item.item_attributes.attribute'])->where(['id' => $inventoryId])->first();
    }
}
