<?php

namespace Tests\Feature;

use App\Models\Consumidor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeituraTest extends TestCase
{
    use RefreshDatabase;

    public function test_registra_leitura_valida(): void
    {
        $user = User::factory()->create();
        $consumidor = Consumidor::create([
            'nome' => 'Joao',
            'endereco' => 'Rua 1',
            'numero_medidor' => '123',
            'telefone' => '999999999',
        ]);

        $response = $this->actingAs($user)->post('/leituras', [
            'consumidor_id' => $consumidor->id,
            'mes_referencia' => 1,
            'ano_referencia' => 2026,
            'leitura_atual' => 8.0,
        ]);

        $response->assertRedirect('/faturas');
        $this->assertDatabaseHas('leituras', [
            'consumidor_id' => $consumidor->id,
            'mes_referencia' => 1,
            'ano_referencia' => 2026,
            'consumo_m3' => 8.0,
        ]);
        $this->assertDatabaseHas('faturas', [
            'consumidor_id' => $consumidor->id,
            'valor_total' => 25.0,
            'status' => 'pendente',
        ]);
    }

    public function test_rejeita_leitura_atual_negativa(): void
    {
        $user = User::factory()->create();
        $consumidor = Consumidor::create([
            'nome' => 'Joao',
            'endereco' => 'Rua 1',
            'numero_medidor' => '124',
            'telefone' => '999999998',
        ]);

        $response = $this->actingAs($user)->post('/leituras', [
            'consumidor_id' => $consumidor->id,
            'mes_referencia' => 1,
            'ano_referencia' => 2026,
            'leitura_atual' => -100,
        ]);

        $response->assertSessionHasErrors('leitura_atual');
    }

    public function test_rejeita_leitura_inconsistente(): void
    {
        $user = User::factory()->create();
        $consumidor = Consumidor::create([
            'nome' => 'Joao',
            'endereco' => 'Rua 1',
            'numero_medidor' => '125',
            'telefone' => '999999997',
        ]);

        $this->actingAs($user)->post('/leituras', [
            'consumidor_id' => $consumidor->id,
            'mes_referencia' => 1,
            'ano_referencia' => 2026,
            'leitura_atual' => 100.0,
        ]);

        $response = $this->actingAs($user)->post('/leituras', [
            'consumidor_id' => $consumidor->id,
            'mes_referencia' => 2,
            'ano_referencia' => 2026,
            'leitura_atual' => 90.0,
        ]);

        $response->assertSessionHasErrors('leitura_atual');
    }
}
