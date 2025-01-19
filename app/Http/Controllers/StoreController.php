<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Models\Store;
use App\Services\StoreService;
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

        return redirect()->route('stores.index');
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

        return redirect()->route('stores.index');
    }

    public function destroy($id)
    {
        $store = Store::findOrFail($id);
        $store->delete();

        return redirect()->route('stores.index');
    }
}
