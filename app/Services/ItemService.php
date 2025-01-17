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
                    'value' => $attr['value']
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
            foreach ($validatedFields['attributes'] as $attr) {
                $attrFound = DB::table('item_attributes')
                    ->where(['id' => $attr['id'], 'item_id' => $itemId]);

                if (!$attrFound->count()) {
                    DB::table('item_attributes')->insert([
                        'item_id' => $item->id,
                        'attribute_id' => $attr['id'],
                        'value' => $attr['value']
                    ]);
                } else {
                    DB::table('item_attributes')
                        ->where(['id' => $attr['id'], 'item_id' => $itemId])
                        ->update(['value' => $attr['value']]);
                }
            }
        }

        return true;
    }
}
