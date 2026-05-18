# 🛠️ ServiceHub - Sistema de Gestão de Tickets (KPMG Challenge)

O **ServiceHub** é uma solução para gestão de tickets de suporte técnico, permitindo a criação de chamados com processamento assíncrono de anexos técnicos (`JSON/TXT`).

---

# 🚀 Tecnologias Utilizadas

* **Backend:** Laravel 13
* **Frontend:** Vue.js 3 + Inertia.js
* **Estilização:** Tailwind CSS
* **Banco de Dados:** PostgreSQL
* **Processamento Assíncrono:** Laravel Queues (Jobs)

---

# 📋 Pré-requisitos

Antes de começar, você precisará ter instalado em seu ambiente:

* PHP 8.3+
* Composer
* Node.js & NPM
* PostgreSQL

---

# 🔧 Instalação e Configuração

Siga os passos abaixo para executar o projeto localmente.

## 1. Clone o repositório

```bash
git clone https://github.com/Izvenn/service-hub.git
cd service-hub
```

---

## 2. Instale as dependências do backend

```bash
composer install
```

---

## 3. Instale as dependências do frontend

```bash
npm install
```

---

## 4. Configure o ambiente

Copie o arquivo `.env.example`:

```bash
cp .env.example .env
```

Gere a chave da aplicação:

```bash
php artisan key:generate
```

---

## 5. Configure o banco de dados

Edite o arquivo `.env` com suas credenciais PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=servicehub
DB_USERNAME=postgres
DB_PASSWORD=senha
```

Execute as migrations e seeders:

```bash
php artisan migrate --seed
```

---

## 6. Compile os assets do frontend

```bash
npm run build
```

Para ambiente de desenvolvimento, você também pode usar:

```bash
npm run dev
```

---

## 7. Execute o worker da fila

O sistema utiliza filas para processar anexos de tickets assincronamente.

```bash
php artisan queue:work
```

---

## 8. Inicie o servidor

```bash
php artisan serve
```

A aplicação estará disponível em:

```text
http://127.0.0.1:8000
```

---


# 📨 Fluxo de Processamento

1. Usuário cria um ticket
2. Upload opcional de anexo técnico
3. Job é enviado para fila
4. Sistema processa o arquivo
5. TicketDetail é atualizado
6. Responsável é notificado
7. Usuários possuem sua pagina de Profile
8. Usuários só podem ver tickets feitos por si ou de projeto compativel/empresa compativel

---

# 👨‍💻 Autor

Desenvolvido por Vinícius Galli.
