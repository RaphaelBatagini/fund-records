<?php

namespace App\Services;

use App\Models\Fund;
use App\Models\FundAlias as Alias;
use App\Events\DuplicateFundWarning;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class FundService
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

    public function getAll(string $name = '', int $manager_id = 0, int $start_year = 0)
    {
        $funds = Fund::query();

        if ($name) {
            $funds->where('name', 'like', '%' . $name . '%');
        }

        if ($manager_id) {
            $funds->where('manager_id', 'like', '%' . $manager_id . '%');
        }

        if ($start_year) {
            $funds->where('start_year', $start_year);
        }

        return $funds->get();
    }

    public function create(string $name, int $start_year, int $manager_id, ?array $aliases = null)
    {
        try {
            $data = [
                'name' => $name,
                'start_year' => $start_year,
                'manager_id' => $manager_id,
                'aliases' => $aliases,
            ];

            $validator = Validator::make($data, $this->getValidationRules());

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $existingFund = Fund::where(function ($query) use ($name) {
                $query->where('name', $name)
                    ->orWhereHas('aliases', function ($query) use ($name) {
                        $query->where('alias', $name);
                    });
            })
            ->where('manager_id', $manager_id)
            ->first();

            $fund = Fund::create($data);

            if ($existingFund) {
                DuplicateFundWarning::dispatch($existingFund, $fund);
            }

            if ($aliases) {
                foreach ($aliases as $alias) {
                    Alias::create([
                        'fund_id' => $fund->id,
                        'alias' => $alias
                    ]);
                }
            }

            return $fund;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }

    public function update(int $id, string $name, int $start_year, int $manager_id, ?array $aliases = null)
    {
        try {
            $fund = Fund::findOrFail($id);

            $data = [
                'name' => $name,
                'start_year' => $start_year,
                'manager_id' => $manager_id,
                'aliases' => $aliases,
            ];

            $validator = Validator::make($data, $this->getValidationRules());

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $fund->update($data);

            if ($aliases) {
                Alias::where('fund_id', $fund->id)->delete();
                foreach ($aliases as $alias) {
                    Alias::create([
                        'fund_id' => $fund->id,
                        'alias' => $alias
                    ]);
                }
            }

            return $fund;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }
    }
}