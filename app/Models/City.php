<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $dates = ['created_at'];

    protected $fillable = [
        'name',  'code', 'state_id'
    ];

    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
