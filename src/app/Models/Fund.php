<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fund extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'start_year',
        'manager_id',
    ];

    /**
     * Get the manager that owns the fund.
     */
    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }

    /**
     * Get the fund aliases for the fund.
     */
    public function aliases()
    {
        return $this->hasMany(FundAlias::class);
    }
}