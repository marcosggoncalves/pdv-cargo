<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PedidoProduto extends Pivot
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'pedido_produto';

    protected $fillable = ['quantidade'];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}
