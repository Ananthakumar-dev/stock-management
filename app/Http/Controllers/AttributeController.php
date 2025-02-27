<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttributeRequest;
use App\Http\Requests\AttributeUpdateRequest;
use App\Models\Attribute;
use App\Models\ItemAttribute;
use App\Services\AttributeService;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttributeController extends Controller
{
    public function index(
        AttributeService $attributeService,
        Request $request
    ) {
        $search = $request->query('search');

        $allAttributes = $attributeService
            ->getAttributes()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%$search%");
            })
            ->orderBy('id', 'DESC')
            ->paginate(PAGINATION)
            ->withQueryString();

        return Inertia::render('Attribute/Index/Index', [
            'attributes' => $allAttributes,
            'initialSearch' => $search
        ]);
    }

    public function create()
    {
        return Inertia::render('Attribute/Add/Index');
    }

    public function store(
        AttributeRequest $attributeRequest
    ) {
        try {
            $validatedFields = $attributeRequest->validated();

            Attribute::create($validatedFields);
        } catch (Exception $e) {
            return redirect()->route('attributes.index')->with('error', 'Something went wrong');
        }

        return redirect()->route('attributes.index')->with('success', 'Attribute created successfully');
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
        try {
            $validatedFields = $attributeUpdateRequest->validated();
    
            $Attribute = Attribute::findOrFail($id);
            $Attribute->update($validatedFields);
        } catch (Exception $e) {
            return redirect()->route('attributes.index')->with('error', 'Something went wrong');
        }

        return redirect()->route('attributes.index')->with('success', 'Attribute updated successfully');
    }

    public function destroy($id)
    {
        try {
            $Attribute = Attribute::findOrFail($id);
    
            $item_attributes = ItemAttribute::where('attribute_id', $id);
            if ($item_attributes->count()) {
                return back()->with('error', 'Cannot delete attribute. Items is associated with this attribute.');
            }
    
            $Attribute->delete();
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong');
        }

        return back()->with('success', 'Attribute deleted successfully');
    }

    public function get(
        AttributeService $attributeService
    ) {
        $allAttributes = $attributeService
            ->getAttributes()
            ->get();

        return [
            'attributes' => $allAttributes
        ];
    }
}
