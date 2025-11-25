<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'user_id',
        'titulo',
        'descricao',
        'quadrante',
        'completed',
        'ordem',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'quadrante' => 'integer',
        'ordem'     => 'integer',
    ];

    /**
     * Filtra tarefas pertencentes ao usuário autenticado
     */
    public function scopeOwned($query)
    {
        return $query->where('user_id', auth()->id());
    }

    /**
     * Ordenação padrão usada na matriz
     */
    public function scopeOrdered($query)
    {
        return $query
            ->orderBy('quadrante')
            ->orderByRaw('ordem IS NULL')
            ->orderBy('ordem');
    }

    /**
     * Relação com usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
