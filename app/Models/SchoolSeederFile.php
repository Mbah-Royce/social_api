<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolSeederFile extends Model
{
    use HasFactory;

    protected $table = 'school_studseederfiles';
    protected $fillable = [
        'path',
        'class_room_id',
        'school_id'
    ];

    /**
     * Get the School that owns the SchoolSeederFileModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function School()
    {
        return $this->belongsTo(School::class);
    }
}
