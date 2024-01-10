<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cost\StoreRequest;
use App\Http\Requests\Cost\UpdateRequest;
use App\Http\Resources\CostResource;
use App\Models\Cost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        return CostResource::collection(Cost::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request): CostResource
    {
        return new CostResource(
            Cost::query()->create([
                'price' => $request->price,
                'comment' => $request->comment,
                'category_id' => $request->category_id,
                'user_id' => 1
            ])
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Cost $cost): CostResource
    {
        return new CostResource($cost);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Cost $cost): CostResource
    {
        $cost->update([
            'price' => $request->price,
            'comment' => $request->comment,
            'category_id' => $request->category_id,
        ]);

        return new CostResource($cost);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cost $cost): JsonResponse
    {
        $cost->delete();

        return response()->json([
            'result' => 'success'
        ]);
    }
}
