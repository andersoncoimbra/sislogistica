<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Transportadora extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'nome',
        'endereco',
        'bairro',
        'cidade',
        'UF',
        'limite_credito',
        'data_analise_credito',
    ];
}
