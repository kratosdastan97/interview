<?php

namespace App\Http\Controllers;

use App\Models\Region;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegionController extends BaseController
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $regions = Region::all();
        return $this->successData($regions);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'status' => 'required'
        ]);

        Region::create($request->all());
        return $this->success();
    }


    /**
     * @param Request $request
     * @param Region $region
     * @return JsonResponse
     */
    public function update(Request $request, Region $region): JsonResponse
    {
        $request->validate([
            'description' => 'required',
            'status' => 'required'
        ]);

        $region->update($request->all());
        return $this->success();
    }

    /**
     * @param Region $region
     * @return JsonResponse
     */
    public function destroy(Region $region): JsonResponse
    {
        $region->delete();
        return $this->success();
    }
}
