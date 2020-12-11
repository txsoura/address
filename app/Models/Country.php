<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'countries';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $dates = ['created_at'];

    protected $fillable = [
        'name',  'code'
    ];
}
