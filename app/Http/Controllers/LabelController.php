<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Http\Requests\StoreLabelRequest;
use App\Http\Requests\UpdateLabelRequest;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return self::paginated(Label::paginate(10), null, 'Labels retrieved successfully', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLabelRequest $request)
    {
        $label = Label::create($request->validated());
        return self::success($label, 'Label created successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Label $label)
    {
        return self::success($label, 'Label retrieved successfully', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLabelRequest $request, Label $label)
    {
        $label->update($request->validated());
        return self::success($label, 'Label updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Label $label)
    {
        $label->delete();
        return self::success(null, 'Label deleted successfully', 200);
    }
}
