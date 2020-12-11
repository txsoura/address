<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use HasFactory, SoftDeletes;

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
