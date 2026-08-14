<?php

namespace App\Services;

class FaturaCalculatorService
{
    private const LIMITE_GRATUITO = 10.0;
    private const LIMITE_COMUM = 20.0;
    private const VALOR_ACIMA_20 = 3.0;

    public function calcular(float $consumoM3, float $taxaFixa, float $valorExcedente): float
    {
        if ($consumoM3 <= self::LIMITE_GRATUITO) {
            return $taxaFixa;
        }

        if ($consumoM3 <= self::LIMITE_COMUM) {
            return $taxaFixa + (($consumoM3 - self::LIMITE_GRATUITO) * $valorExcedente);
        }

        $excedenteAcima20 = $consumoM3 - self::LIMITE_COMUM;
        $valorFaixa2 = (self::LIMITE_COMUM - self::LIMITE_GRATUITO) * $valorExcedente;
        $valorFaixa3 = $excedenteAcima20 * self::VALOR_ACIMA_20;

        return $taxaFixa + $valorFaixa2 + $valorFaixa3;
    }
}
