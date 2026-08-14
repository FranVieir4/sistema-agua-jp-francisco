# Análise Arquitetural

## Elementos identificados

| Elemento | Arquivo encontrado | Responsabilidade |
|----------|--------------------|------------------|
| Model | app/Models/Consumidor.php | Representa consumidores do sistema de água, incluindo dados de cadastro e relacionamento com leituras. |
| Model | app/Models/Leitura.php | Representa leitura de consumo com atributos de referência, valor anterior, atual e consumo. |
| Model | app/Models/Fatura.php | Representa faturas geradas a partir de leituras de consumo. |
| Controller | app/Http/Controllers/LeituraController.php | Coordena o fluxo de registro de leituras, criando leitura e fatura. |
| Controller | app/Http/Controllers/ConsumidorController.php | Gerencia cadastro, edição e visualização de consumidores. |
| Form Request | app/Http/Requests/StoreLeituraRequest.php | Valida entrada do registro de leitura, garantindo formato e existência do consumidor. |
| Service | app/Services/FaturaCalculatorService.php | Calcula o valor total da fatura conforme regras de cobrança. |

## Observações de arquitetura

- O projeto segue a arquitetura MVC do Laravel.
- A responsabilidade de validação de formulário foi concentrada em `StoreLeituraRequest`.
- A validação de regra de negócio de leitura inconsistente foi movida para o Model `Leitura`.
- O cálculo de fatura permanece em `FaturaCalculatorService`, evitando lógica no Controller.

## Decisões adotadas

- `StoreLeituraRequest` contém regras de validação de tipos e existência de `consumidor_id`.
- `Leitura` ganhou um método `leituraValida` para garantir que `leitura_atual >= leitura_anterior`.
- `LeituraController` agora coordena fluxo e delega validação e cálculo.
- `FaturaCalculatorService` foi atualizado para aplicar três faixas de cobrança.

## Uso de IA

Ferramenta utilizada: GitHub Copilot

O assistente auxiliou na análise arquitetural e na refatoração do fluxo de registro de leituras, incluindo a separação de responsabilidades e a escrita de testes.
