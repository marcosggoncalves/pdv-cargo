<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoHistorico extends Model
{
    use HasFactory;

    protected $table = 'produto_historico';

    protected $fillable = ['tipo', 'quantidade', 'produto_id'];

    protected $hidden = ['updated_at', 'produto_id', 'id'];
}
