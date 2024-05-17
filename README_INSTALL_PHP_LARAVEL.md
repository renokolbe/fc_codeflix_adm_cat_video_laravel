0. Repositório GIT para Laravel 9 com Eloquent - Quick Start
https://github.com/codeedu/micro-admin-videos-php/tree/laravel-9-quick-start

1. Instalação inicial de projeto com o PHP
composer init
composer dump-autoload

2. Instalando o PHP Unit
composer require --dev phpunit/phpunit ^9.5

3. Instalando o Mockery
composer require --dev mockery/mockery

3.1 Rakit/Validation (Implementa validações) -- https://github.com/rakit/validation
composer require "rakit/validation"

4. Execução dos testes
./vendor/bin/phpunit   --> executa tudo
./vendor/bin/phpunit  <caminho/para/arquivo.php> --> executa arquivo

5. Sobre os Testes em PHP
- Arquivos devem conter no nome a palavra Test, como por exemplo CategoryUnitTest;
- A classe de Teste (normalmente o mesmo nome do arquivo) deve _extender_ TestCase;
- As funções dentro da classe devem conter o prefixo test, como por exemplo testAttributes();

6. Package UUID
composer require ramsey/uuid

7. Rodando os Testes com Artisan
php artisan test 
php artisan test --stop-on-failure
Alguns atalhos criados:
- (Ctrl+R) - Executa o teste da função
- (Ctrl+F) - Executa o teste do arquivo
- (Ctrl+L) - Executa o teste do projeto

8. Criação de Models (DB) / Migration
php artisan make:model Category -m
php artisan make:model CastMember -m

9. Geração do Banco
php artisan migrate

10. Criação de Testes de Unidade (Unit)
php artisan make:test App\\Models\\CategoryUnitTest --unit

11. Criação de Teste E2E (Feature)
php artisan make:test App\\Repositories\\Eloquent\\CategoryEloquentRepository
php artisan make:test App\\Repositories\\Eloquent\\CastMemberEloquentRepository

12. Criação de Factory para Testes
php artisan make:factory CategoryFactory
php artisan make:factory CastMemberFactory
php artisan make:factory VideoFactory

13. Criação de Teste de Integração (useCase + Repository)
php artisan make:test Core\\Usecase\\Category\\CreateCategoryUseCaseTest

14. Criação de Teste de Unidade de Controllers
php artisan make:test App\\Http\\Controllers\\Api\\CategpryControllerUnitTest --unit

15. Criação de Controllers
php artisan make:controller Api\\CategoryController
php artisan make:controller Api\\GenreController --api

16. Criação de Resource para Apoio ao Retorno de APIS
php artisan make:resource CategoryResource

17. Criação do Teste de Integração de Controller
php artisan make:test App\\Http\\Controllers\\Api\\CategpryControllerTest

18. Criação de Objeto Request
php artisan make:request StoreCategoryRequest
php artisan make:request StoreGenreRequest

19. Criaçaõ de Teste de APIs
php artisan make:test Api\\CategoryApiTest

20. Criação de Teste de Unidade de Dominio
php artisan make:test Domain\\Entity\\GenreUnitTest --unit

21. Criação de Teste de Unidade de UseCase
php artisan make:test UseCase\\Genre\\ListGenresUseCaseUnitTest --unit

22. Criação de tabela de relacionamento Muitos <-> Muitos
php artisan make:migration create_category_genre_table
php artisan make:migration CreateCategoryVideoTable
php artisan make:migration CreateGenreVideoTable
php artisan make:migration CreateCastMemberVideoTable

23. Execução da geração do Migrate
php artisan migrate
php artisan migrate:refresh
php artisan migrate:fresh

24. Ferramenta de Manipulação de Dados do Laravel (tinker)
php artisan tinker

25. Criação de Service Provider (para fazer o bind das Infraestruturas com as Implementações Reais)
php artisan make:provide CleanArchServiceProvider
Depois que o Service Provider eh criado, deve-se configurá-lo em config/app.php

26. Inversão de Dependência - Registrar o bind (singleton) na classe de ServiceProvider cruada (ou no AppServiceProvider Padrao)
Dentro do método register()
$this->app_singleton([interface]:class, [implementação_real]:class)
Exemplo:
        $this->app->singleton(
            CategoryRepositoryInterface::class, 
            CategoryEloquentRepository::class
        );

27. Criação de Listener de Eventos
php artisan make:listener SendVideoToMicroEncoder

Os eventos precisam ser linkados em app/Providers/EventServiceProvider

28. Criação de teste de Serviços
php artisan make:test App\\Services\\FileStorageTest

29. Package para Trabalhar com o Google Cloud Storage (github.com/spatie/laravel-google-cloud-storage)
composer require spatie/laravel-google-cloud-storage

30. Quando criar um arquivo de Helpers (funções comuns)
    a) Colocar normalmente em app/Helpers/<arquivo.php>
    b) Testar se o Método ou Função Não existe, apra evitar error
    c) Incluir a mesma no composer.json (autoload: files[path/to/file.php])
    d) Executar o comamndo de autoload
        composer dump-autoload

31. Package para trabalahr com o RabbitMq ([php-amqplib/php-amqplib](https://github.com/php-amqplib/php-amqplib))
composer require php-amqplib/php-amqplib

32. Criação de arquivo de comando:
php artisan make:command RabbitMQCommand

33. Package de Guard para Keycloak  (https://github.com/robsontenorio/laravel-keycloak-guard)
composer require robsontenorio/laravel-keycloak-guard

34. Criar MODEL de USER para Autenticação/Guard
php artisan make:model User

35. Package de Validação de Código (Laravel/Pint)
composer require laravel/pint --dev

Exemplo - Geração de Dados
\App\Models\Genre::factory()->count(100)->create();


### IP Interno do Docker #### 172.17.0.1

#### ATENCAO ## TESTES VIA POSTMAN ## Deve-se executar o Conatiner fora do VSCode

### Integração Micro Serviço Encoded ####
- Subir containers 
    cd /home/rkolbe/FullCyle/CodeFlix/microsservico-encoder
    docker compose up -d
    docker compose exec app make server

- No RabbitMq - http://localhost:15672 (rabbitmq / rabbitmq)
    a) criar Exchange dlx
    b) Fazer o bind da queue Video com o Exchange dlx
	
### Teste de Conexão UDP ### containers de ElastickSearch
echo "teste do prompt" | nc -u -w0 127.0.0.1 4718

## Processos de CI/CD