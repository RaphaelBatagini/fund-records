<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use App\Models\Fund;
use App\Models\FundAlias as Alias;
use App\Services\FundService;

class FundsController extends Controller
{
    public function __construct(public FundService $fundService) {}

    /**
     * Display a listing of funds filtered by name, fund manager and/or start year.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            return response()->json(
                $this->fundService->getAll(
                    $request->input('name'),
                    $request->input('manager_id'),
                    $request->input('start_year')
                )
            );
        } catch (ValidationExcepion $e) {
            return response()->json($e->errors(), 400);
        } catch (\Exception $e) {
            return response()->json('Unknown failure. Please try again later.', 500);
        }
    }

    /**
     * Store a newly created fund in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            return response()->json(
                $this->fundService->create(
                    $request->input('name'),
                    $request->input('start_year'),
                    $request->input('manager_id'),
                    $request->input('aliases')
                ),
                201
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json($e->errors(), 400);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
            return response()->json('Unknown failure. Please try again later.', 500);
        }
    }

    /**
     * Update the specified fund in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $fund
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            return response()->json(
                $this->fundService->update(
                    $id,
                    $request->input('name'),
                    $request->input('start_year'),
                    $request->input('manager_id'),
                    $request->input('aliases')
                )
            );
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 400);
        } catch (\Exception $e) {
            return response()->json('Unknown failure. Please try again later.', 500);
        }
    }
}
