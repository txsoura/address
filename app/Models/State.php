<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'states';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $dates = ['created_at'];

    protected $fillable = [
        'name',  'code', 'country_id'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
