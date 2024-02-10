<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutoImagens extends Model
{
    use HasFactory;

    protected $table = 'produto_imagens';

    protected $fillable = ['url', 'produto_id'];

    protected $hidden = ['created_at', 'updated_at'];
}
