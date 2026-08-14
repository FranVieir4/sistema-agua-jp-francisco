<?php

namespace Tests\Unit;

use App\Services\FaturaCalculatorService;
use PHPUnit\Framework\TestCase;

class FaturaCalculatorServiceTest extends TestCase
{
    public function test_calculo_para_oito_m3_retorna_vinte_cinco_reais(): void
    {
        $service = new FaturaCalculatorService();

        $valor = $service->calcular(8.0, 25.0, 2.0);

        $this->assertSame(25.0, $valor);
    }

    public function test_calculo_para_quinze_m3_retorna_trinta_e_cinco_reais(): void
    {
        $service = new FaturaCalculatorService();

        $valor = $service->calcular(15.0, 25.0, 2.0);

        $this->assertSame(35.0, $valor);
    }

    public function test_calculo_para_vinte_m3_retorna_quarenta_e_cinco_reais(): void
    {
        $service = new FaturaCalculatorService();

        $valor = $service->calcular(20.0, 25.0, 2.0);

        $this->assertSame(45.0, $valor);
    }

    public function test_calculo_para_vinte_e_cinco_m3_retorna_sessenta_reais(): void
    {
        $service = new FaturaCalculatorService();

        $valor = $service->calcular(25.0, 25.0, 2.0);

        $this->assertSame(60.0, $valor);
    }
}
