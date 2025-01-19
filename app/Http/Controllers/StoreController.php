<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Models\Inventory;
use App\Models\Store;
use App\Services\StoreService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Inertia\Inertia;

class StoreController extends Controller
{
    public function index(
        StoreService $storeService,
        Request $request
    ) {
        $search = $request->query('search');

        $allStores = $storeService
            ->getStores()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
            })
            ->orderBy('id', 'DESC')
            ->paginate(PAGINATION)
            ->withQueryString();

        return Inertia::render('Store/Index/Index', [
            'stores' => $allStores,
            'initialSearch' => $search
        ]);
    }

    /**
     * store create form
     */
    public function create()
    {
        return Inertia::render('Store/Add/Index');
    }

    public function store(
        StoreRequest $storeRequest
    ) {
        try {
            $validatedFields = $storeRequest->validated();
            $validatedFields['address'] = [
                'block' => $validatedFields['block'],
                'street' => $validatedFields['street'],
                'city' => $validatedFields['city'],
            ];

            Store::create(
                Arr::only(
                    $validatedFields,
                    ['name', 'description', 'address', 'phone']
                )
            );
        } catch (Exception $e) {
            return redirect()->route('stores.index')->with('error', 'Something went wrong');
        }

        return redirect()->route('stores.index')->with('success', 'Store created successfully');
    }

    public function show(
        int $id
    ) {
        $store = Store::findOrFail($id);

        return Inertia::render('Store/Edit/Index', [
            'store' => $store
        ]);
    }

    public function update(
        StoreRequest $storeRequest,
        $id
    ) {
        try {
            $validatedFields = $storeRequest->validated();
            $validatedFields['address'] = [
                'block' => $validatedFields['block'],
                'street' => $validatedFields['street'],
                'city' => $validatedFields['city'],
            ];

            $store = Store::findOrFail($id);
            $store->update(
                Arr::only(
                    $validatedFields,
                    ['name', 'description', 'address', 'phone']
                )
            );
        } catch (Exception $e) {
            return redirect()->route('stores.index')->with('error', 'Something went wrong');
        }

        return redirect()->route('stores.index')->with('success', 'Store updated successfully');
    }

    public function destroy($id)
    {
        try {
            $store = Store::findOrFail($id);

            $inventory = Inventory::where('store_id', $id);
            if ($inventory->count()) {
                return back()->with('error', 'Cannot delete store. Inventory is associated with this store.');
            }

            $store->delete();
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong');
        }

        return back()->with('success', 'Store deleted successfully');
    }
}
