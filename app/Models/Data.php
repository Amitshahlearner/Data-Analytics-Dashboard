<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Data extends Model
{
	use HasFactory;

	protected $fillable = [
        'end_year', 'intensity', 'sector', 'topic', 'insight', 'url',
        'region', 'start_year', 'impact', 'added', 'published', 'city',
        'country', 'relevance', 'pestle', 'source', 'title', 'likelihood'
    ];
}
