
<p align="center">
<a href="hhttps://www.adoorei.com.br/" target="_blank">
<img src="https://adoorei.s3.us-east-2.amazonaws.com/images/loje_teste_logoadoorei_1662476663.png" width="160"></a>
</p>

# Desafio desenvolvedor back-end: Wellington Cristian de Almeida Santos
# wellington.cris799@gmail.com

Nossa API foi criado os seguintes endpoints

* Listar produtos disponíveis
* Cadastrar nova venda
* Consultar vendas realizadas
* Consultar uma venda específica
* Cancelar uma venda
* Cadastrar novas produtos a uma venda



### É essencial no seu código:
* Migrate/Seed UTILIZADO
* API Rest respeitando os formatos.
* Commits regulares.

### Pontos que irão destacar você neste desafio:
* Utilizado Dockers
* Implementado testes unitarios
* Criado Documentação com Postman
* Aplicado conceitos para melhor código


Aqui está uma adaptação da documentação da API para o formato de um `README.md`, pronto para ser utilizado no seu projeto. Isso fornecerá um guia claro e acessível para qualquer pessoa que esteja interagindo com seu projeto ou utilizando sua API.

```markdown
# Documentação da API

## Introdução

Este documento oferece uma visão geral dos endpoints disponíveis na API, incluindo detalhes sobre como realizar requisições e o que esperar nas respostas.

## Endpoints Disponíveis

### Listar Produtos Disponíveis

- **GET /api/products**
  
  Retorna uma lista de todos os produtos disponíveis.

#### Resposta

- **200 OK**
  
  ```json
  [
      {
          "id": 1,
          "name": "Produto 1",
          "price": 100.00,
          "description": "Descrição do produto 1",
          "created_at": "2024-03-03T20:56:56.000000Z",
          "updated_at": "2024-03-03T20:56:56.000000Z"
      },
      {
          "id": 2,
          "name": "Produto 2",
          "price": 200.00,
          "description": "Descrição do produto 2",
          "created_at": "2024-03-04T13:59:15.000000Z",
          "updated_at": "2024-03-04T13:59:15.000000Z"
      }
  ]
  ```

### Cadastrar Nova Venda

- **POST /api/sales**
  
  Cadastra uma nova venda com produtos específicos.

#### Body

```json
{
    "amount": 2999.99,
    "status": "completed",
    "products": [
        {
            "id": 1,
            "amount": 2,
            "price": 150.00
        }
    ]
}
```

#### Resposta

- **201 Created**
  
  ```json
  {
      "id": 1,
      "amount": 2999.99,
      "status": "completed",
      "products": [
          {
              "product_id": 1,
              "amount": 2,
              "price": 150.00
          }
      ],
      "created_at": "2024-03-04T17:03:14.000000Z",
      "updated_at": "2024-03-04T17:03:14.000000Z"
  }
  ```

### Consultar Vendas Realizadas

- **GET /api/sales**
  
  Retorna uma lista de todas as vendas realizadas, incluindo os produtos associados.

### Consultar Uma Venda Específica

- **GET /api/sales/{id}**
  
  Retorna detalhes de uma venda específica pelo seu ID, incluindo os produtos associados.

### Cancelar Uma Venda

- **PUT /api/sales/{id}/cancel**
  
  Cancela uma venda específica pelo seu ID.

#### Resposta

- **200 OK**
  
  ```json
  { "message": "Venda cancelada com sucesso" }
  ```

### Cadastrar Novos Produtos a Uma Venda

- **PUT /api/sales/{id}**
  
  Atualiza uma venda existente adicionando novos produtos ou atualizando os existentes.

## Testes

Todos os endpoints devem ser testados utilizando ferramentas como Postman ou Insomnia.
