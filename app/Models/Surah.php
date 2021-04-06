<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surah extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function verses()
    {
        return $this->hasMany(Verse::class);
    }

    public function sajdas()
    {
        return $this->hasMany(Sajda::class);
    }

    public function rukus()
    {
        return $this->hasMany(Ruku::class);
    }
}
