<?php

namespace Tests\Feature\Core\Usecase\Video;

use App\Models\{
    CastMember,
    Category,
    Genre
};
use Core\Domain\Repository\{
    CastMemberRepositoryInterface, 
    CategoryRepositoryInterface,
    GenreRepositoryInterface, 
    VideoRepositoryInterface
};
use Core\UseCase\Interfaces\{
    TransactionInterface
};
use Exception;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Tests\Stubs\{
    UploadFileStub,
    VideoEventStub
};
use Tests\TestCase;
use Throwable;

abstract class BaseVideoUseCase extends TestCase
{
    abstract function useCase(): string;
    abstract function inputDTO(
        array $categories = [],
        array $genres = [],
        array $castMembers = [],
        ? array $videoFile = null,
        ? array $trailerFile = null,
        ? array $bannerFile = null,
        ? array $thumbFile = null,
        ? array $thumbHalf = null,

    ): object;

    /**
     * @dataProvider provider
     */
    public function testActionUseCase(
        int $categoriesCount,
        int $genresCount,
        int $castMembersCount,
        bool $withMediaVideo = false,
        bool $withTrailer = false,
        bool $withBanner = false,
        bool $withThumb = false,
        bool $withThumbHalf = false
    )
    {
        // $useCase = new ($this->useCase())(
        //     $this->app->make(VideoRepositoryInterface::class),
        //     $this->app->make(TransactionInterface::class),
        //     new UploadFileStub(),
        //     new VideoEventStub(),
        //     $this->app->make(CategoryRepositoryInterface::class),
        //     $this->app->make(GenreRepositoryInterface::class),
        //     $this->app->make(CastMemberRepositoryInterface::class)
        // );

        $sut = $this->makeSut();

        $categoriesIds = Category::factory()->count($categoriesCount)->create()->pluck('id')->toArray();
        $genresIds = Genre::factory()->count($genresCount)->create()->pluck('id')->toArray();
        $castMembersIds = CastMember::factory()->count($castMembersCount)->create()->pluck('id')->toArray();

        $fakeMedia = UploadedFile::fake()->create('video.mp4', 1024, 'video/mp4');
        $media = [
            'tmp_name' => $fakeMedia->getPathname(),
            'name' => $fakeMedia->getClientOriginalName(),
            'type' => $fakeMedia->getMimeType(),
            'error' => $fakeMedia->getError(),
        ];

        $input = $this->inputDTO(
            categories: $categoriesIds,
            genres: $genresIds,
            castMembers: $castMembersIds,
            videoFile: $withMediaVideo ? $media : null,
            trailerFile: $withTrailer ? $media : null,
            bannerFile: $withBanner ? $media : null,
            thumbFile: $withThumb ? $media : null,
            thumbHalf: $withThumbHalf ? $media : null
        );

        $response = $sut->exec($input);

        $this->assertNotNull($response->id);
        $this->assertEquals($input->title, $response->title);
        $this->assertEquals($input->description, $response->description);
        // $this->assertEquals($input->yearLaunched, $response->yearLaunched);
        // $this->assertEquals($input->opened, $response->opened);
        // $this->assertEquals($input->rating, $response->rating);
        // $this->assertEquals($input->duration, $response->duration);

        $this->assertCount(count($input->categories), $response->categories);
        $this->assertEqualsCanonicalizing($input->categories, $response->categories);
        $this->assertCount(count($input->genres), $response->genres);
        $this->assertEqualsCanonicalizing($input->genres, $response->genres);
        $this->assertCount(count($input->castMembers), $response->castMembers);
        $this->assertEqualsCanonicalizing($input->castMembers, $response->castMembers);

        $this->assertTrue($withMediaVideo ? $response->videoFile !== null : $response->videoFile === null);
        $this->assertTrue($withTrailer ? $response->trailerFile !== null : $response->trailerFile === null);
        $this->assertTrue($withBanner ? $response->bannerFile !== null : $response->bannerFile === null);
        $this->assertTrue($withThumb ? $response->thumbFile !== null : $response->thumbFile === null);
        $this->assertTrue($withThumbHalf ? $response->thumbHalf !== null : $response->thumbHalf === null);

    }

    protected function provider(): array
    {
        return [
            'Test without any IDs' => [
                'categoriesCount' => 0,
                'genresCount' => 0,
                'castMembersCount' => 0,
            ],
            'Test with all IDs' => [
                'categoriesCount' => 1,
                'genresCount' => 1,
                'castMembersCount' => 1
            ],
            'Test without categories' => [
                'categoriesCount' => 0,
                'genresCount' => 1,
                'castMembersCount' => 1
            ],
            'Test without categories and genres' => [
                'categoriesCount' => 0,
                'genresCount' => 0,
                'castMembersCount' => 1
            ],
            'Test without categories and castMembers' => [
                'categoriesCount' => 0,
                'genresCount' => 1,
                'castMembersCount' => 0
            ],
            'Test with all IDs and Media and Trailer' => [
                'categoriesCount' => 1,
                'genresCount' => 1,
                'castMembersCount' => 1,
                'withMediaVideo' => true,
                'withTrailer' => true
            ],
            'Test with all IDs and Only Images' => [
                'categoriesCount' => 1,
                'genresCount' => 1,
                'castMembersCount' => 1,
                'withMediaVideo' => false,
                'withTrailer' => false,
                'withBanner' => true,
                'withThumb' => true,
                'withThumbHalf' => true
            ],
            'Test with all IDs and Medias andImages' => [
                'categoriesCount' => 1,
                'genresCount' => 1,
                'castMembersCount' => 1,
                'withMediaVideo' => true,
                'withTrailer' => true,
                'withBanner' => true,
                'withThumb' => true,
                'withThumbHalf' => true
            ],
        ];
    }

    protected function makeSut()
    {
        return new ($this->useCase())(
            $this->app->make(VideoRepositoryInterface::class),
            $this->app->make(TransactionInterface::class),
            new UploadFileStub(),
            new VideoEventStub(),
            $this->app->make(CategoryRepositoryInterface::class),
            $this->app->make(GenreRepositoryInterface::class),
            $this->app->make(CastMemberRepositoryInterface::class)
        );
        
    }

    // Ao usar a Notação abaixo, a funcção não previsa ser criada com o sufixo Test !
    /**
     * @test
     */

     public function transactionException()
     {
        // Forca uma Excecao quando do Inicio do Begin Transaction para testar que não houve gravação efetiva no banco
        Event::listen(TransactionBeginning::class, function () {
            throw new Exception('begin transaction');
        });

        try {
            $categoriesIds = Category::factory()->count(5)->create()->pluck('id')->toArray();
            $genresIds = Genre::factory()->count(5)->create()->pluck('id')->toArray();
            $castMembersIds = CastMember::factory()->count(5)->create()->pluck('id')->toArray();

            $sut = $this->makeSut();
            $sut->exec($this->inputDTO(
                categories: $categoriesIds,
                genres: $genresIds,
                castMembers: $castMembersIds
            ));

            // Nao deve chegar aqui - Pois estamos testando a Exception que deve ser tratada no Catch
            $this->assertTrue(false);

        } catch (Throwable $th) {
            // Como teve um expection NÃO pode ter registro criado no Banco
            $this->assertDatabaseCount('videos', 0);
            $this->assertDatabaseCount('category_video', 0);
            $this->assertDatabaseCount('genre_video', 0);
            $this->assertDatabaseCount('cast_member_video', 0);
        }
     }

     /**
      * @test
      */

      public function uploadFilesException()
      {
        // Forca uma Excecao quando do Store de Arquivos para testar que não houve gravação efetiva no banco
        Event::listen(UploadFileStub::class, function () {
            //dd('upload files');
            throw new Exception('upload files');
        });

        try {
            $categoriesIds = Category::factory()->count(5)->create()->pluck('id')->toArray();
            $genresIds = Genre::factory()->count(5)->create()->pluck('id')->toArray();
            $castMembersIds = CastMember::factory()->count(5)->create()->pluck('id')->toArray();

            $sut = $this->makeSut();
            $input = $this->inputDTO(
                categories: $categoriesIds,
                genres: $genresIds,
                castMembers: $castMembersIds,
                videoFile: [
                    'name' => 'video.mp4',
                    'type' => 'video/mp4',
                    'tmp_name' => 'video.mp4',
                    'error' => 0,
                ],
                bannerFile: [
                    'name' => 'banner.png',
                    'type' => 'image/png',
                    'tmp_name' => 'banner.png',
                    'error' => 0,
                ]
            );

            $sut->exec($input);
            // Nao deve chegar aqui - Pois estamos testando a Exception que deve ser tratada no Catch
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertDatabaseCount('videos', 0);
            $this->assertDatabaseCount('category_video', 0);
            $this->assertDatabaseCount('genre_video', 0);
            $this->assertDatabaseCount('cast_member_video', 0);
            $this->assertDatabaseCount('medias_video', 0);
            $this->assertDatabaseCount('images_video', 0);
        }
    }

    /**
     * @test
     */

     public function eventException()
     {
         // Forca uma Excecao quando do Disparo de Evento de Arquivos para testar que não houve gravação efetiva no banco
         Event::listen(VideoEventStub::class, function () {
            //dd('video created event');
            throw new Exception('video created event');
        });

        try {
            $categoriesIds = Category::factory()->count(5)->create()->pluck('id')->toArray();
            $genresIds = Genre::factory()->count(5)->create()->pluck('id')->toArray();
            $castMembersIds = CastMember::factory()->count(5)->create()->pluck('id')->toArray();

            $sut = $this->makeSut();
            $input = $this->inputDTO(
                categories: $categoriesIds,
                genres: $genresIds,
                castMembers: $castMembersIds,
                videoFile: [
                    'name' => 'video.mp4',
                    'type' => 'video/mp4',
                    'tmp_name' => 'video.mp4',
                    'error' => 0,
                ],
                trailerFile: [
                    'name' => 'trailer.mp4',
                    'type' => 'video/mp4',
                    'tmp_name' => 'trailer.mp4',
                    'error' => 0,
                ],
                bannerFile: [
                    'name' => 'banner.png',
                    'type' => 'image/png',
                    'tmp_name' => 'banner.png',
                    'error' => 0,
                ],
                thumbFile: [
                    'name' => 'thumb.png',
                    'type' => 'image/png',
                    'tmp_name' => 'thumb.png',
                    'error' => 0,
                ],
                thumbHalf: [
                    'name' => 'thumb.png',
                    'type' => 'image/png',
                    'tmp_name' => 'thumb.png',
                    'error' => 0,
                ],
            );

            $sut->exec($input);
            // Nao deve chegar aqui - Pois estamos testando a Exception que deve ser tratada no Catch
            $this->assertTrue(false);
        } catch (Throwable $th) {
            $this->assertDatabaseCount('videos', 0);
            $this->assertDatabaseCount('category_video', 0);
            $this->assertDatabaseCount('genre_video', 0);
            $this->assertDatabaseCount('cast_member_video', 0);
            $this->assertDatabaseCount('medias_video', 0);
            $this->assertDatabaseCount('images_video', 0);
        }
     }

}
