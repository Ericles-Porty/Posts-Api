Fiz esse projeto para relembrar php e docker. A ideia é criar uma api simples para gerenciar posts utilizando o micro framework Slim. 

Para subir os containers, basta rodar o comando `docker-compose up --build` na raiz do projeto.

Para criar a tabela de posts, basta rodar o seguinte comando no terminal do container do banco de dados:
```sql
CREATE TABLE IF NOT EXISTS posts (
    id SERIAL PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL
);
```

A api estará disponível em `http://localhost:8080`.

Para testar os endpoints, você pode usar o arquivo api.http que está na raiz do projeto com o plugin REST Client do VSCode.

Endpoints:
- GET /api/posts
- GET /api/posts/{id}
- POST /api/posts
- PUT /api/posts/{id}
- DELETE /api/posts/{id}

Observações: 
- Eu ainda não fiz a sanitização dos dados de entrada, então talvez seja possível fazer sql injection. 
- Os testes unitários ainda não estão prontos.
- A documentação da api ainda não está pronta. (Swagger)
- As variáveis de ambiente ainda não estão configuradas. Todas as configurações estão hardcoded.
