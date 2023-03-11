# apiDelivros
Api  de Livraria no qual permite: Criar livro e Seus indices / Listar Livros / Buscar por titulo  do livro /  Visualizar XML montado com os dados

## Arquivos 

- Possui o arquivo na pasta raiz, chamado 'docker-compose.yml'. Onde é possível criar a instância do postgres através de poucos comandos do docker.
  ### Docker-Compose.yml:
    version: '3.8'

    services:
        nginx:
            image: nginx:1.19.6
            container_name: nginx
            volumes:
                - ./src:/usr/share/nginx/html
                - ./default.conf:/etc/nginx/conf.d/default.conf
            ports:
                - 8087:80
            links:
                - php_server
        php_server:
            container_name: php_server
            image: php:7.4.14-fpm-buster
            volumes:
                - ./src:/usr/share/nginx/html
        postgres:
            container_name: postgres_lumen
            image: postgres:latest
            environment:
                POSTGRES_USER: postgres_lumen_app
                POSTGRES_PASSWORD: '@CT1_Guerreiro'
                PGDATA: /data/postgres
            ports:
                - "5432:5432"
                
 
  - Na raiz do projeto, existe um arquivo chamado 'updateSQL', onde possui as tabelas que foram criadas no Postgresql.

# Authenticação via api, foi utilizado um recurso que o Laravel nos disponibiliza chamado 'Passport'.
### Rodar comando para executar na porta :8000

- [  php -S localhost:8000 -t public  ]

#Endpoints e Parametros de chamada:
 ## Autenticação:
  ### Login - POST
      - Host: http://localhost:8000
      - EndPoint: /api/login
      - Parametros:
      - Campos Requiridos: 
        - email
        - password;
      
   ### Cadastro Usuário - POST
      - Host: http://localhost:8000
      - EndPoint: /api/register
      - Parametros:
      - Campos Requiridos: 
        - name;
        - email
        - password;
        - c_password;
        
   ### Listar Livros - GET
    - Host: http://localhost:8000
    - EndPoint: /api/livros
    - Parametros:
    - Campos não obrigatórios: 
      - titulo;
      - titulo_do_indice;
      
    ### Cadastrar Livros - POST
  - Host: http://localhost:8000
  - EndPoint: /api/livros
  - Parametros:
  - JSON - Body:
  
    {
      "titulo": "Novo livro 10",
        "indices": [
            {
                "titulo": "introdução 10",
                "pagina": 1,
                "subindices": [
                    {
                        "titulo": "indice 10",
                        "pagina": 2,
                        "subindices": [
                        ]
                    }
                ]
            }
        ]
    }
  
  ### Importar Indices - POST
    - Host: http://localhost:8000
    - EndPoint: /api/livros/{$id}/importar-indices-xml
