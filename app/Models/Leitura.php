<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leitura extends Model
{
    protected $fillable = ['consumidor_id', 'mes_referencia', 'ano_referencia', 'leitura_anterior', 'leitura_atual', 'consumo_m3'];

    protected $casts = [
        'consumidor_id' => 'integer',
        'mes_referencia' => 'integer',
        'ano_referencia' => 'integer',
        'leitura_anterior' => 'float',
        'leitura_atual' => 'float',
        'consumo_m3' => 'float',
    ];

    public function consumidor()
    {
        return $this->belongsTo(Consumidor::class);
    }

    public function fatura()
    {
        return $this->hasOne(Fatura::class);
    }

    public function leituraValida(): bool
    {
        return $this->leitura_atual >= $this->leitura_anterior;
    }
}
