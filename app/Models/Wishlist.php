<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    public function boarding(){
        return $this->belongsTo(Boarding::class,'boardings_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'users_id');
    }

    protected $guarded = [];
}
