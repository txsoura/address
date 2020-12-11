<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'addresses';
    protected $primaryKey = 'id';
    public $incrementing = true;

    protected $dates = ['created_at', 'deleted_at'];

    protected $fillable = [
        'name',  'number', 'street', 'postcode', 'complement', 'district', 'longitude', 'latitude', 'user_id', 'city_id'
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
