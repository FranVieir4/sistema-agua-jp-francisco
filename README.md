# Sistema de Controle de Consumo de Água

Projeto Laravel para cadastro de consumidores, registro de leituras, cálculo de consumo e geração de faturas.

## Tecnologias

- Laravel 13 (PHP 8.3+)
- SQLite (para execução local por conveniência)
- Vite, Tailwind (front-end)

## Rodando localmente (rápido)

1. Clone o repositório:
```bash
git clone https://github.com/VieiraFrancisco1/sistema-agua-jp-francisco.git
cd sistema-agua-jp-francisco
```

2. Instale dependências PHP e JS:
```bash
composer install
npm install
npm run build
```

3. Prepare ambiente e banco SQLite:
```bash
cp .env.example .env
php artisan key:generate
mkdir -p database
touch database/database.sqlite
sed -i "s/DB_CONNECTION=.*/DB_CONNECTION=sqlite/" .env
sed -i "s/DB_DATABASE=.*/DB_DATABASE=database\/database.sqlite/" .env
php artisan migrate --force
php artisan db:seed --class=DatabaseSeeder
```

4. Rodar testes:
```bash
php artisan test
```

5. Iniciar servidor e acessar:
```bash
php artisan serve --host=127.0.0.1 --port=8000
```
Abra: http://127.0.0.1:8000

## Credenciais de exemplo (seed)

- Admin: `admin@agua.com` / `password`
- Leiturista: `leiturista@agua.com` / `password`

## Notas

- Use SQLite para testes rápidos; para produção configure um banco como MySQL/Postgres no `.env`.
- Arquivos importantes: `app/Services/FaturaCalculatorService.php`, `app/Http/Requests/StoreLeituraRequest.php`, `app/Http/Controllers/LeituraController.php`.

Se quiser, eu posso commitar este `README.md` e adicionar um `AdminUserSeeder` separado para criação automática do admin (já há usuários criados no `DatabaseSeeder`).