<?php

namespace App\Http\Requests;

use App\Models\Leitura;
use Illuminate\Foundation\Http\FormRequest;

class StoreLeituraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'leitura_anterior' => $this->previousLeituraAtual(),
        ]);
    }

    public function rules(): array
    {
        return [
            'consumidor_id' => ['required', 'integer', 'exists:consumidores,id'],
            'mes_referencia' => ['required', 'integer', 'min:1', 'max:12'],
            'ano_referencia' => ['required', 'integer', 'min:2000'],
            'leitura_anterior' => ['required', 'numeric', 'min:0'],
            'leitura_atual' => ['required', 'numeric', 'min:0'],
        ];
    }

    private function previousLeituraAtual(): float
    {
        if (! $this->filled('consumidor_id')) {
            return 0.0;
        }

        $ultimaLeitura = Leitura::where('consumidor_id', $this->input('consumidor_id'))
            ->orderByDesc('ano_referencia')
            ->orderByDesc('mes_referencia')
            ->first();

        return $ultimaLeitura?->leitura_atual ?? 0.0;
    }
}
