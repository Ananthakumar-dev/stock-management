<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttributeRequest;
use App\Http\Requests\AttributeUpdateRequest;
use App\Models\Attribute;
use App\Services\AttributeService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttributeController extends Controller
{
    public function index(
        AttributeService $attributeService
    ) {
        $allAttributes = $attributeService
            ->getAttributes()
            ->paginate(PAGINATION);

        return Inertia::render('Attribute/Index/Index', [
            'attributes' => $allAttributes
        ]);
    }

    public function create() {
        return Inertia::render('Attribute/Add/Index');
    }

    public function store(
        AttributeRequest $attributeRequest
    ) {
        $validatedFields = $attributeRequest->validated();

        Attribute::create($validatedFields);

        return redirect()->route('attributes.index');
    }

    public function show(
        int $id
    ) {
        $attribute = Attribute::findOrFail($id);

        return Inertia::render('Attribute/Edit/Index', [
            'attribute' => $attribute
        ]);
    }

    public function update(
        AttributeUpdateRequest $attributeUpdateRequest,
        $id
    ) {
        $validatedFields = $attributeUpdateRequest->validated();

        $Attribute = Attribute::findOrFail($id);
        $Attribute->update($validatedFields);

        return redirect()->route('attributes.index');
    }

    public function destroy($id)
    {
        $Attribute = Attribute::findOrFail($id);
        $Attribute->delete();

        return redirect()->route('attributes.index');
    }

    public function get(
        AttributeService $attributeService
    )
    {
        $allAttributes = $attributeService
            ->getAttributes()
            ->get();

        return [
            'attributes' => $allAttributes
        ];
    }
}
