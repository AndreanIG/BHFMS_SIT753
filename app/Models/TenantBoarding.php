<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantBoarding extends Model
{
    use HasFactory;
    public function rentTransactions(){
        return $this->hasMany(RentTransaction::class);
    }
    
    protected $guarded = [];
}
