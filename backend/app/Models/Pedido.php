<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedido';

    protected $fillable = ['data_pedido', 'situacao'];

    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'data_pedido' => 'datetime: d/m/Y'
    ];

    public function produtos()
    {
        return $this->belongsToMany(Produto::class)->withPivot('id', 'quantidade');
    }
}
