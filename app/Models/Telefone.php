<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\Mask;

class Telefone extends Model
{
    use HasFactory;

    protected $table = 'telefones';

    protected $fillable = [
        'pessoa_id',
        'numero'
    ];

    public function getNumeroAttribute($value)
    {
        return Mask::format($value, 'phone');
    }
}
