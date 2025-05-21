<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardingType extends Model
{
    use HasFactory;
    
    public function boardings(){
        return $this->hasMany(Boarding::class);
    }

    protected $guarded = [];
}
