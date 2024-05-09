<?php

namespace App\Providers;

use App\Events\{
    VideoEvent
};
use App\Repositories\Eloquent\CastMemberEloquentRepository;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use App\Repositories\Eloquent\GenreEloquentRepository;
use App\Repositories\Eloquent\VideoEloquentRepository;
use App\Repositories\Transaction\DBTransaction;
use App\Services\AMQP\AMQPInterface;
use App\Services\AMQP\PhpAMQPService;
use App\Services\{
    Storage\FileStorage
};
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\VideoRepositoryInterface;
use Core\UseCase\Interfaces\FileStorageInterface;
use Core\UseCase\Interfaces\TransactionInterface;
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;
use Illuminate\Support\ServiceProvider;

class CleanArchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Informa para o PHP/Laravel qual a relação entre a Interface de Intfraestrutura e o Repositorio Definitivo

        $this->bindRepositories();

        $this->app->singleton(
            FileStorageInterface::class,
            FileStorage::class
        );

        $this->app->singleton(
            VideoEventManagerInterface::class,
            VideoEvent::class
        );

        /**
         * DB Transaction
         */
        $this->app->bind(
            TransactionInterface::class,
            DBTransaction::class
        );

        /**
         * Services
         */
        $this->app->bind(
            AMQPInterface::class,
            PhpAMQPService::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    private function bindRepositories()
    {
        $this->app->singleton(
            CategoryRepositoryInterface::class,
            CategoryEloquentRepository::class
        );

        $this->app->singleton(
            GenreRepositoryInterface::class,
            GenreEloquentRepository::class
        );

        $this->app->singleton(
            CastMemberRepositoryInterface::class,
            CastMemberEloquentRepository::class
        );

        $this->app->singleton(
            VideoRepositoryInterface::class,
            VideoEloquentRepository::class
        );

    }
}
