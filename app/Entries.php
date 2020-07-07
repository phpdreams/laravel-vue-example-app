<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Entries extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'label', 'entry_time', 'amount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
