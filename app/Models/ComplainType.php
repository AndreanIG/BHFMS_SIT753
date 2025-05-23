<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplainType extends Model
{
    use HasFactory;

    public function complains(){
        return $this->hasMany(Complain::class);
    }

    protected $guarded = [];
}
