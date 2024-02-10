<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;

    protected $table = 'produto';

    protected $fillable = ['descricao', 'valor_venda', 'estoque'];

    protected $hidden = ['created_at', 'updated_at'];

    public function imagens()
    {
        return $this->hasMany(ProdutoImagens::class);
    }

    public function historico()
    {
        return $this->hasMany(ProdutoHistorico::class);
    }

    public function pedidos()
    {
        return $this->belongsToMany(Pedido::class)->withPivot('quantidade');
    }
}
