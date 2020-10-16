<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'DOB', 'gender', 'place_of_birth', 'adopted_by', 'adoption_status',
        'adopted_on',
    ];

    protected $casts = [
        'DOB' => 'date',
    ];

    public function parent(){
        return $this->hasOne(Parents::class, 'adopted', 'adopted_by');
    }

}
