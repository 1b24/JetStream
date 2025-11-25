<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'user_id',
        'titulo',
        'quadrante',
        'completed',
        'ordem',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'quadrante' => 'integer',
        'ordem' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
