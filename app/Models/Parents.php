<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parents extends Model
{
    use HasFactory;

    protected $table = 'parents';

    protected $fillable = [
        'address', 'phone', 'id_number', 'user_id', 'adopted',
    ];

    public function children(){
        return $this->hasMany(Child::class, 'adopted', 'adopted_by');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
