<?php

namespace App\Support;

class Quadrantes
{
    public const LISTA = [
        1 => [
            'titulo'      => 'Urgente e Importante',
            'bg'          => 'bg-red-100',
            'titleColor'  => 'text-red-700',
            'buttonColor' => 'bg-red-600',
            'editBorder'  => '#dc2626',
        ],
        2 => [
            'titulo'      => 'Não Urgente e Importante',
            'bg'          => 'bg-yellow-100',
            'titleColor'  => 'text-yellow-700',
            'buttonColor' => 'bg-yellow-600',
            'editBorder'  => '#ca8a04',
        ],
        3 => [
            'titulo'      => 'Urgente e Não Importante',
            'bg'          => 'bg-blue-100',
            'titleColor'  => 'text-blue-700',
            'buttonColor' => 'bg-blue-600',
            'editBorder'  => '#2563eb',
        ],
        4 => [
            'titulo'      => 'Não Urgente e Não Importante',
            'bg'          => 'bg-green-100',
            'titleColor'  => 'text-green-700',
            'buttonColor' => 'bg-green-600',
            'editBorder'  => '#16a34a',
        ],
    ];
}
