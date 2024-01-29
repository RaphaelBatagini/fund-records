<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Fund;
use App\Models\FundAlias as Alias;

class FundsController extends Controller
{
    private function getValidationRules()
    {
        return [
            'name' => 'required|string|max:255',
            'start_year' => 'required|integer|min:1900|max:' . date('Y'),
            'manager_id' => 'required|exists:managers,id',
            'aliases' => 'array|nullable',
        ];
    }

    /**
     * Display a listing of funds filtered by name, fund manager and/or start year.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $funds = Fund::query();

        if ($request->has('name')) {
            $funds->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->has('manager_id')) {
            $funds->where('manager_id', 'like', '%' . $request->input('manager_id') . '%');
        }

        if ($request->has('start_year')) {
            $funds->where('start_year', $request->input('start_year'));
        }

        return response()->json($funds->get());
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
            $request->validate(self::getValidationRules());

            $existingFund = Fund::where(function ($query) use ($request) {
                $query->where('name', $request->input('name'))
                    ->orWhereHas('aliases', function ($query) use ($request) {
                        $query->where('alias', $request->input('name'));
                    });
            })
            ->where('manager_id', $request->input('manager_id'))
            ->first();

            $fund = Fund::create($request->all());

            if ($existingFund) {
                event(new \App\Events\DuplicateFundWarning($existingFund, $fund));
            }

            if ($request->has('aliases')) {
                $aliases = $request->input('aliases');
                foreach ($aliases as $alias) {
                    Alias::create([
                        'fund_id' => $fund->id,
                        'alias' => $alias
                    ]);
                }
            }

            return response()->json($fund, 201);
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
            $fund = Fund::findOrFail($id);

            $request->validate(self::getValidationRules());

            $fund->update($request->all());

            if ($request->has('aliases')) {
                $aliases = $request->input('aliases');
                Alias::where('fund_id', $fund->id)->delete();
                foreach ($aliases as $alias) {
                    Alias::create([
                        'fund_id' => $fund->id,
                        'alias' => $alias
                    ]);
                }
            }

            return response()->json($fund);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json($e->errors(), 400);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error($e->getMessage());
            return response()->json('Unknown failure. Please try again later.', 500);
        }
    }
}
