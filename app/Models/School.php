<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone1',
        'phone2',
        'lat',
        'lng',
        'logo',
        'password',
        'cover_picture',
        'account_status',
    ];

    /**
     * Get all of the students for the School
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students()
    {
        return $this->hasMany(Studemt::class);
    }

    /**
     * Get all of the classRooms for the School
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function classRooms()
    {
        return $this->hasMany(ClassRoom::class);
    }

    /**
     * Get all of the studSeederFiles for the School
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function studSeederFile()
    {
        return $this->hasMany(SchoolSeederFile::class);
    }

    /**
     * The admins of the school
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function user()
    {
        return $this->belongsToMany(User::class, 'school_admin', 'school_id', 'user_id');
    }
    
}
