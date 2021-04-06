<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manzil extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function startSurah()
    {
        return $this->belongsTo(Surah::class, 'start_surah_id');
    }

    public function endSurah()
    {
        return $this->belongsTo(Surah::class, 'end_surah_id');
    }

    public function startVerse()
    {
        return $this->belongsTo(Verse::class, 'start_verse_id');
    }

    public function endVerse()
    {
        return $this->belongsTo(Verse::class, 'end_verse_id');
    }
}
