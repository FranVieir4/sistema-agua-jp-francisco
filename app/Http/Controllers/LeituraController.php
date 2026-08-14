<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLeituraRequest;
use App\Models\Consumidor;
use App\Models\Leitura;
use App\Models\Fatura;
use App\Models\ConfiguracaoTaxa;
use App\Services\FaturaCalculatorService;

class LeituraController extends Controller
{
    public function __construct(
        private FaturaCalculatorService $calculator
    ) {}

    public function create()
    {
        $consumidores = Consumidor::orderBy('nome')->get();
        return view('leituras.create', compact('consumidores'));
    }

    public function store(StoreLeituraRequest $request)
    {
        $data = $request->validated();

        $jaExiste = Leitura::where('consumidor_id', $data['consumidor_id'])
            ->where('mes_referencia', $data['mes_referencia'])
            ->where('ano_referencia', $data['ano_referencia'])
            ->exists();

        if ($jaExiste) {
            return back()->withErrors(['leitura' => 'Já existe leitura registrada para este consumidor neste mês/ano.'])->withInput();
        }

        $consumo = $data['leitura_atual'] - $data['leitura_anterior'];

        $leitura = Leitura::make([
            'consumidor_id' => $data['consumidor_id'],
            'mes_referencia' => $data['mes_referencia'],
            'ano_referencia' => $data['ano_referencia'],
            'leitura_anterior' => $data['leitura_anterior'],
            'leitura_atual' => $data['leitura_atual'],
            'consumo_m3' => $consumo,
        ]);

        if (! $leitura->leituraValida()) {
            return back()
                ->withErrors(['leitura_atual' => 'A leitura atual não pode ser menor que a anterior (' . number_format($data['leitura_anterior'], 2, ',', '.') . ' m³).'])
                ->withInput();
        }

        $leitura->save();

        $config = ConfiguracaoTaxa::first() ?? ConfiguracaoTaxa::create(['taxa_fixa' => 25, 'valor_excedente' => 2]);

        $valorTotal = $this->calculator->calcular(
            $consumo,
            $config->taxa_fixa,
            $config->valor_excedente,
        );

        Fatura::create([
            'leitura_id' => $leitura->id,
            'consumidor_id' => $leitura->consumidor_id,
            'valor_total' => $valorTotal,
            'status' => 'pendente',
        ]);

        return redirect()->route('faturas.index')->with('success', 'Leitura registrada. Valor da fatura: R$ ' . number_format($valorTotal, 2, ',', '.'));
    }
}