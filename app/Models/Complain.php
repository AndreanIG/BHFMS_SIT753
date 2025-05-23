<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complain extends Model
{
    use HasFactory;
    
    public function complainType(){
        return $this->belongsTo(ComplainType::class,'complain_types_id');
    }

    protected $guarded = [];
}
