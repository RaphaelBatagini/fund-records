<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Fund;

class FundsController extends Controller
{
    private function getValidationRules()
    {
        return [
            'name' => 'required|string|max:255',
            'start_year' => 'required|integer|min:1900|max:' . date('Y'),
            'manager_id' => 'required|exists:managers,id',
        ];
    }

    /**
     * Display a listing of funds filtered by name, fund manager and/or start year.
     *
     * @return \Illuminate\Http\Response
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

        return $funds->get();
    }

    /**
     * Store a newly created fund in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(self::getValidationRules());

        return Fund::create($request->all());
    }

    /**
     * Update the specified fund in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $fund
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $fund = Fund::findOrFail($id);

        $request->validate(self::getValidationRules());

        $fund->update($request->all());

        return $fund;
    }
}
