# Travel Orders API Onfly

API REST desenvolvida com Laravel + Docker.


## 🚀 Tecnologias

* PHP 8.3
* Laravel 12
* MySQL
* Docker + Docker Compose
* Sanctum (autenticação via tokens)
* PHPUnit (testes automatizados)


## ⚡ Funcionalidades



1. **Criar pedido de viagem**
   * Campos: `id`, `nome do solicitante`, `destino`, `data de ida`, `data de volta`, `status` (`solicitado`, `aprovado`, `cancelado`)
2. **Consultar pedido por ID**
3. **Listar pedidos**
   * Opções de filtro: `status`, período de datas, `destino`
4. **Atualizar status de pedido**
   * Somente usuários admin podem alterar para `aprovado` ou `cancelado`
5. **Cancelar pedido**
   * Só pode ser cancelado se ainda não tiver sido aprovado
6. **Notificação de aprovação/cancelamento**
   * Notificação enviada ao usuário solicitante (implementação via JSON de resposta ou futura integração)


## 🚀 Setup rápido com Docker



1. Garanta que **Docker** e **Docker Compose** estão instalados.
2. Copie o arquivo `setup.sh` para a raiz do projeto.
3. Dê permissão de execução:

```javascript
chmod +x setup.sh
```


4\. Execute o script:

```javascript
./setup.sh
```

Isso fará:

* Subir containers Docker (`travel_app` + MySQL)
* Instalar dependências PHP
* Criar `.env` e gerar chave da aplicação
* Rodar migrations do banco
* Limpar caches
* Criar usuários de teste (`user@test.com` e `admin@test.com`)
* Criar 5 pedidos de teste para o usuário normal
* Exibir tokens para autenticação via Postman

> A API estará acessível em `http://localhost:8000/api/ping`.

## ⚙️ Setup do Projeto

Clone o repositório:

```bash
git clone https://github.com/csc62/travel-onfly
cd travel-orders
```

## 🔑 Tokens de teste

O `setup.sh` gera automaticamente tokens de teste para Postman:

* **Usuário normal:** `user@test.com` / `12345678`
* **Usuário admin:** `admin@test.com` / `12345678`

Use o token como **Bearer Token** em todas as requisições autenticadas.


## 📦 Endpoints da API

### Criar pedido

```javascript
POST /api/travel-orders
Headers: Authorization: Bearer <token>
Body JSON:
{
    "destination": "São Paulo",
    "departure_date": "2026-03-01",
    "return_date": "2026-03-10"
}
```

### Listar pedidos

```javascript
GET /api/travel-orders
Headers: Authorization: Bearer <token>
```

* Opções de filtro via query string:
  * `status=solicitado`
  * `destination=Paris`
  * `from_date=2026-03-01&to_date=2026-03-31`

### Consultar pedido por ID

```javascript
GET /api/travel-orders/{id}
Headers: Authorization: Bearer <token>
```

### Atualizar status (somente admin)

```javascript
PATCH /api/travel-orders/{id}/status
Headers: Authorization: Bearer <admin_token>
Body JSON:
{
    "status": "aprovado"
}
```


## 🧪 Testes automatizados

Rodar todos os testes:

```javascript
docker exec -it travel_app php artisan test
```

O projeto inclui testes para:

* Criação de pedidos
* Listagem de pedidos
* Consulta de pedido por ID
* Atualização de status (somente admin)


## ⚙️ Observações

* Cada usuário só pode ver, criar e consultar **seus próprios pedidos**.
* Usuários admin podem aprovar ou cancelar pedidos de qualquer usuário.
* O frontend não está incluído; testes devem ser feitos via **Postman** ou **insomnia**.
* Para qualquer alteração no `.env`, rode `docker exec -it travel_app php artisan config:clear`.


