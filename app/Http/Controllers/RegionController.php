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
        try {
            $regions = Region::all();
            return $this->successData($regions);
        } catch (\Exception $e) {
            return $this->error();
        }
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'description' => 'required',
                'status' => 'required'
            ]);
            Region::create($request->all());
            return $this->success();
        } catch (\Exception $e) {
            return $this->error();
        }
    }


    /**
     * @param Request $request
     * @param Region $region
     * @return JsonResponse
     */
    public function update(Request $request, Region $region): JsonResponse
    {
        try {
            $request->validate([
                'description' => 'required',
                'status' => 'required'
            ]);
            $region->update($request->all());
            return $this->success();
        } catch (\Exception $e) {
            return $this->error();
        }
    }

    /**
     * @param Region $region
     * @return JsonResponse
     */
    public function destroy(Region $region): JsonResponse
    {
        try {
            $region->delete();
            return $this->success();
        } catch (\Exception $e) {
            return $this->error();
        }
    }
}
