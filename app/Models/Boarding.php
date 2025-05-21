<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boarding extends Model
{
    use HasFactory;
    public function boardingType(){
        return $this->belongsTo(BoardingType::class, 'type_id');
    }

    public function facilities(){
        return $this->belongsToMany(FacilityDetail::class, 'facilities', 'boarding_id', 'facility_id');
    }

    public function tenantBoardings(){
        return $this->belongsToMany(User::class, 'tenant_boardings', 'boarding_id', 'user_id');
    }

    public function ownerBoardings(){
        return $this->belongsToMany(User::class, 'owner_boardings', 'boarding_id', 'user_id',);
    }
    public function managerBoardings(){
        return $this->belongsToMany(User::class, 'manager_boardings', 'boarding_id', 'user_id',);
    }

    public function wishlists(){
        return $this->belongsToMany(User::class, 'wishlists', 'boarding_id', 'user_id');
    }

    public function reviews(){
        return $this->belongsToMany(User::class, 'reviews', 'boarding_id', 'user_id');
    }

    public function complains(){
        return $this->belongsToMany(User::class, 'complains', 'boarding_id', 'user_id');
    }

    public function images(){
        return $this->hasMany(BoardingImage::class);
    }

    public function images_limit_one(){
        return $this->images()->limit(1);
    }

    protected $guarded = [];
}