<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'password',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'dob',
        'gender',
        'profile_picture',
        'cover_picture',
        'pwd_changed',
        'account_status',
        'status'
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

    /**
     * Get all of the studentAccounts for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function studentAccounts()
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Hash user's password upon creation
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] =  Hash::make($value);
    }

    /**
     * The school which the user administers
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function school()
    {
        return $this->belongsToMany(Role::class, 'school_admin', 'user_id', 'school_id');
    }
}
