<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasFactory;

    protected $table = 'pessoas';

    protected $fillable = [
        'nome',
        'cpf',
        'email',
        'data_nasc',
        'nacionalidade',
    ];

    public function telefones()
    {
        return $this->hasMany(Telefone::class);
    }
}
