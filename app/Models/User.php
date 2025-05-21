<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function rentTransactionHeaders(){
        return $this->hasMany(RentTransactionHeader::class);
    }

    public function ownerBoardings(){
        return $this->hasMany(OwnerBoarding::class);
    }

    public function complains(){
        return $this->hasMany(Complain::class);
    }

    public function managerBoardings(){
        return $this->belongsToMany(Boarding::class, 'manager_boardings', 'user_id', 'boarding_id');
    }

    public function role() {
        return $this->belongsTo(UserRole::class,'user_role_id');
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_name',
        'gender',
        'date_of_birth',
        'user_role_id',
        'phone',
        'profile_picture',
        'email',
        'password',
        'confirm_password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
