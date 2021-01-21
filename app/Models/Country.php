<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $dates = ['created_at'];

    protected $fillable = [
        'name',  'code'
    ];
}
