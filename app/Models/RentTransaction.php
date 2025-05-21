<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentTransaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function TenantBoarding(){
        return $this->belongsTo(TenantBoarding::class,'tenants_id');
    }

    public function paymentMethod(){
        return $this->belongsTo(PaymentMethod::class,'payment_methods_id');
    }
    public function TransactionType(){
        return $this->belongsTo(TransactionType::class,'transaction_types_id');
    }
}
