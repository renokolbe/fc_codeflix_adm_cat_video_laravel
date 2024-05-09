Programação orientada à Clean Archtecture - PHP/Laravel
1. Criação da Entidade
    source: 
        - Entidades: src/Core/Domain/Entity
        - ValueObjetcs: src/Core/Domain/ValueObject
        - Expections: src/Core/Domain/Exception
        - Enums: src/Core/Domain/Enum
        - Validation: src/Core/Domain/Validation
        - Interface de Repositorio: src/Core/Domain/Repository
    testes de Unidade: 
        - tests/Unit/Domain/Entity
            - Sintaxe: php artisan make:test [camino\\para\\Arquivo] --unit
            - Exemplo: php artisan make:test Domain\\Entity\\CastMemberUnitTest --unit
2. Criação dos UsesCases da Entidade
    source: 
        - UseCases: src/Core/Domain/UseCase
        - DTO: src/Core/Domain/DTO
        - 
    testes de unidade (com Mockery):
        - tests/Unit/UseCase
            - Sintaxe: php artisan make:test [caminho\\para\\Arquivo] --unit
            - Exemplo: php artisan make:test UseCase\\CastMember\\DeleteCastMemberUseCaseUnitTest --unit
3. Criação de Infraestrutura
    source:
        - Models: App/Models
            - Sintaxe: php artisan make:model [Arquivo] -m
            - Exemplo: php artisan make:model CastMember -m
        - Migration: Database/Migrations
        - Factories: Database/Factories
            - Sintaxe: php artisan make:factory [Arquivo]
            - Exemplo: php artisan make:factory CastMemberFactory
        - Repositórios: App/Repositories/Eloquent
    testes:
        - App/Repositories/Eloquent
            - Sintaxe: php artisan make:test [caminho\\para\\arquivo]
            - Exemplo: php artisan make:test App\\Repositories\\Eloquent\\CastMemberEloquentRepository
4. Teste de Integração (Entidade->Usecase->Infraestrutura)
    testes:
        - Tests/Feature/Core/Usecase
            - Sintaxe: php artisan make:test [caminho\\para\\ListGenreUseCaseTest]
            - Exemplo: php artisan make:test Core\\Usecase\\Genre\\ListGenreUseCaseTest
5. Criação de Controllers (e Requests)
    source:
        - App/Http/Controllers/Api
            - Sintaxe: php artisan make:controller [caminho\\para\\arquivo] --api
            - Exemplo: php artisan make:controller Api\\GenreController --api
    testes:
        - Tests/Feature/Http/Controllers/Api
            - Sintaxe: php artisan make:test [caminho\\para\\arquivo]
            - Exemplo: php artisan make:test App\\Http\\Controllers\\Api\\CategporyControllerTest
6. Criação de APIs
    Configurar rota (route/api.php)
        - Sintaxe: Route::apiResource(['/caminho'], [Resource::class])
        - Exemplo: Route::apiResource('/cast_members', CastMemberResource::class);
    source:
    testes:
7. Testes E2E
    testes: 
        - Tests/Feature/Api
            - Sintaxe: php artisan make:test [caminho\\para\\arquivo]
            - Exemplo: php artisan make:test Api\\CastMemberApiTest
8. Binds de Interface com Repositórios
    - Arquivo App/Providers/AppServiceProvider.php : 
        - Sintaxe: $this->app->singleton([Repositorio_Interface]::class, [RepositorioReal]::class);
        - Exemplo: $this->app->singleton(CategoryRepositoryInterface::class, CategoryEloquentRepository::class);