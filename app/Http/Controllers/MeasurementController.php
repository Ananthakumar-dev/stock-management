<?php

namespace App\Http\Controllers;

use App\Http\Requests\MeasurementRequest;
use App\Http\Requests\MeasurementUpdateRequest;
use App\Models\Measurement;
use App\Services\MeasurementService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MeasurementController extends Controller
{
    public function index(
        MeasurementService $measurementService
    ) {
        $allMeasurements = $measurementService
            ->getMeasurements()
            ->paginate(PAGINATION);

        return Inertia::render('Measurement/Index/Index', [
            'measurements' => $allMeasurements
        ]);
    }

    public function create() {
        return Inertia::render('Measurement/Add/Index');
    }

    public function store(
        MeasurementRequest $measurementRequest
    ) {
        $validatedFields = $measurementRequest->validated();

        Measurement::create($validatedFields);

        return redirect()->route('measurements.index');
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
    )
    {
        $validatedFields = $measurementUpdateRequest->validated();

        $measurement = Measurement::findOrFail($id);
        $measurement->update($validatedFields);

        return redirect()->route('measurements.index');
    }

    public function destroy($id)
    {
        $measurement = Measurement::findOrFail($id);
        $measurement->delete();

        return redirect()->route('measurements.index');
    }
}
