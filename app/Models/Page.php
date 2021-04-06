<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
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

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['image'];

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

    public function getImageAttribute()
    {
        return asset("storage/quran_images/{$this->id}.png");
    }
}
