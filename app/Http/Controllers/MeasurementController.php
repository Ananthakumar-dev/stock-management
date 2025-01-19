<?php

namespace App\Http\Controllers;

use App\Http\Requests\MeasurementRequest;
use App\Http\Requests\MeasurementUpdateRequest;
use App\Models\Item;
use App\Models\Measurement;
use App\Services\MeasurementService;
use Exception;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MeasurementController extends Controller
{
    public function index(
        MeasurementService $measurementService,
        Request $request
    ) {
        $search = $request->query('search');

        $allMeasurements = $measurementService
            ->getMeasurements()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%$search%");
            })
            ->orderBy('id', 'DESC')
            ->paginate(PAGINATION)
            ->withQueryString();

        return Inertia::render('Measurement/Index/Index', [
            'measurements' => $allMeasurements,
            'initialSearch' => $search
        ]);
    }

    public function create()
    {
        return Inertia::render('Measurement/Add/Index');
    }

    public function store(
        MeasurementRequest $measurementRequest
    ) {
        try {
            $validatedFields = $measurementRequest->validated();

            Measurement::create($validatedFields);
        } catch (Exception $e) {
            return redirect()->route('measurements.index')->with('error', 'Something went wrong');
        }

        return redirect()->route('measurements.index')->with('success', 'Measurement created successfully');
    }

    public function show(
        int $id
    ) {
        $measurement = Measurement::findOrFail($id);

        return Inertia::render('Measurement/Edit/Index', [
            'measurement' => $measurement
        ]);
    }

    public function update(
        MeasurementUpdateRequest $measurementUpdateRequest,
        $id
    ) {
        try {
            $validatedFields = $measurementUpdateRequest->validated();
    
            $measurement = Measurement::findOrFail($id);
            $measurement->update($validatedFields);
        } catch (Exception $e) {
            return redirect()->route('measurements.index')->with('error', 'Something went wrong');
        }

        return redirect()->route('measurements.index')->with('success', 'Measurement updated successfully');
    }

    public function destroy($id)
    {
        try {
            $measurement = Measurement::findOrFail($id);
    
            $item = Item::where('measurement_id', $id);
            if ($item->count()) {
                return back()->with('error', 'Cannot delete measurement. Items is associated with this measurement.');
            }

            $measurement->delete();
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong');
        }

        return back()->with('success', 'Item deleted successfully');
    }
}
