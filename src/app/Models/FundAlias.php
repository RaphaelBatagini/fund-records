<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundAlias extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'alias',
        'fund_id',
    ];

    /**
     * Get the fund that owns the alias.
     */
    public function fund()
    {
        return $this->belongsTo(Fund::class);
    }
}