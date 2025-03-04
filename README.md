# Carteira Financeira - Laravel

Este projeto consiste em uma carteira financeira desenvolvida com Laravel, utilizando Laravel Breeze para autenticação. Ele permite aos usuários criar contas, realizar depósitos, transferências e reverter transações.

## Funcionalidades
- Criar contas de usuário
- Login de usuários
- Depositar valores
- Enviar valores para outros usuários
- Verificar todas as transações realizadas pelo extrato da conta
- Reverter transferências e depósitos

## Requisitos
- PHP 8+
- Composer
- MySQL
- Laravel 10+
- Node.js e NPM (para gerenciar dependências do front-end)

## Instalação

1. Clone o repositório:
```sh
git clone https://github.com/FelipeSDS23/carteira_financeira.git
```

2. Acesse o diretório do projeto:
```sh
cd carteira_financeira
```

3. Instale as dependências do Laravel:
```sh
composer install
```

4. Instale as dependências do front-end:
```sh
npm install && npm run build
```

5. Configure o arquivo `.env`:
```sh
cp .env.example .env
```

Edite o arquivo `.env` e configure as seguintes variáveis para conexão com o banco de dados e para envio de e-mail de confirmação no login:
```env
APP_LOCALE=pt_BR
APP_TIMEZONE=America/Sao_Paulo

DB_CONNECTION=mysql
DB_HOST=seu_host
DB_PORT=sua_porta
DB_DATABASE=carteira_financeira
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

MAIL_MAILER: O tipo de transporte de e-mail (usualmente smtp).
MAIL_HOST=O servidor SMTP do seu provedor de e-mail.
MAIL_PORT=A porta para o servidor SMTP (geralmente 587 para TLS, 465 para SSL).
MAIL_USERNAME=Seu nome de usuário de e-mail.
MAIL_PASSWORD=A senha do seu e-mail.
MAIL_FROM_ADDRESS="carteira_financeira@app.com"
MAIL_FROM_NAME="${APP_NAME}"
```

6. Gere a chave da aplicação:
```sh
php artisan key:generate
```

7. Execute as migrações do banco de dados:
```sh
php artisan migrate
```

8. Inicie o servidor local:
```sh
php artisan serve
```

## Uso
1. Acesse `http://127.0.0.1:8000` no navegador.
2. Registre dois usuários para testar as transferências.
3. Faça login e realize depósitos na conta.
4. Envie valores para outro usuário e teste as funcionalidades de reversão de transferências e depósitos pelo extrato da conta.
