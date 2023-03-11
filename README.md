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

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
