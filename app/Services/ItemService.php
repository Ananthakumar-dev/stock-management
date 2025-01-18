<?php

namespace App\Services;

use App\Models\Item;
use Attribute;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ItemService
{
    /**
     * get all Items
     */
    public function getItems()
    {
        $items = DB::table('items')
            ->select([
                'items.id',
                'items.measurement_id',
                'items.name',
                'items.quantity',
                'items.availability',
                'measurements.name AS measurement_name',
            ])
            ->join('measurements', function ($join) {
                $join->on('items.measurement_id', '=', 'measurements.id');
            });

        return $items;
    }

    /**
     * create item
     */
    public function store(
        $validatedFields
    ) {
        $item = Item::create(
            Arr::except(
                $validatedFields,
                ['attributes']
            )
        );

        if (isset($validatedFields['attributes']) && count($validatedFields['attributes'])) {
            foreach ($validatedFields['attributes'] as $attr) {
                DB::table('item_attributes')->insert([
                    'item_id' => $item->id,
                    'attribute_id' => $attr['id'],
                    'value' => $attr['value'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return true;
    }

    /**
     * update item
     */
    public function update(
        $validatedFields,
        $itemId
    ) {
        $item = Item::where('id', $itemId)->update(
            Arr::except(
                $validatedFields,
                ['attributes']
            )
        );

        if (isset($validatedFields['attributes']) && count($validatedFields['attributes'])) {
            $this->updateAttributes($itemId, $validatedFields['attributes']);
        }

        return true;
    }

    public function updateAttributes($itemId, $attributes)
    {
        // Fetch existing attributes for the item
        $existingAttributes = DB::table('item_attributes')
            ->where('item_id', $itemId)
            ->pluck('id', 'attribute_id') // Key: attribute_id, Value: id
            ->toArray();

        $attributeIdsInRequest = collect($attributes)->pluck('attribute_id')->all();

        // 1. Remove attributes not present in the request
        DB::table('item_attributes')
            ->where('item_id', $itemId)
            ->whereNotIn('attribute_id', $attributeIdsInRequest)
            ->delete();

        foreach ($attributes as $attribute) {
            if (isset($existingAttributes[$attribute['attribute_id']])) {
                // 2. Update existing attribute
                DB::table('item_attributes')
                    ->where('id', $existingAttributes[$attribute['attribute_id']])
                    ->update([
                        'value' => $attribute['value'],
                        'updated_at' => now(),
                    ]);
            } else {
                // 3. Insert new attribute
                DB::table('item_attributes')->insert([
                    'item_id' => $itemId,
                    'attribute_id' => $attribute['attribute_id'],
                    'value' => $attribute['value'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
