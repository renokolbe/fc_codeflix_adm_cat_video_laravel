<?php

namespace Tests\Feature\Core\Usecase\Video;

use Core\Domain\Enum\Rating;
use Core\UseCase\Video\Create\CreateVideoUseCase;
use Core\UseCase\Video\Create\DTO\CreateInputVideoDTO;

class CreateVideoUseCaseTest extends BaseVideoUseCase
{
    public function useCase(): string
    {
        return CreateVideoUseCase::class;
    }

    public function inputDTO(
        array $categories = [],
        array $genres = [],
        array $castMembers = [],
        ?array $videoFile = null,
        ?array $trailerFile = null,
        ?array $bannerFile = null,
        ?array $thumbFile = null,
        ?array $thumbHalf = null,

    ): object {
        return new CreateInputVideoDTO(
            title: 'New Title',
            description: 'New Description',
            yearLaunched: 2021,
            opened: true,
            rating: Rating::L,
            duration: 120,
            categories: $categories,
            genres: $genres,
            castMembers: $castMembers,
            videoFile: $videoFile,
            trailerFile: $trailerFile,
            bannerFile: $bannerFile,
            thumbFile: $thumbFile,
            thumbHalf: $thumbHalf
        );
    }

    // Movidos para a classe BaseVideoUseCase

    // // Ao usar a Notação abaixo, a funcção não previsa ser criada com o sufixo Test !
    // /**
    //  * @test
    //  */

    //  public function transactionException()
    //  {
    //     // Forca uma Excecao quando do Inicio do Begin Transaction para testar que não houve gravação efetiva no banco
    //     Event::listen(TransactionBeginning::class, function () {
    //         throw new Exception('begin transaction');
    //     });

    //     try {
    //         $categoriesIds = Category::factory()->count(5)->create()->pluck('id')->toArray();
    //         $genresIds = Genre::factory()->count(5)->create()->pluck('id')->toArray();
    //         $castMembersIds = CastMember::factory()->count(5)->create()->pluck('id')->toArray();

    //         $sut = $this->makeSut();
    //         $sut->exec($this->inputDTO(
    //             categories: $categoriesIds,
    //             genres: $genresIds,
    //             castMembers: $castMembersIds
    //         ));

    //         // Nao deve chegar aqui - Pois estamos testando a Exception que deve ser tratada no Catch
    //         $this->assertTrue(false);

    //     } catch (Throwable $th) {
    //         // Como teve um expection NÃO pode ter registro criado no Banco
    //         $this->assertDatabaseCount('videos', 0);
    //         $this->assertDatabaseCount('category_video', 0);
    //         $this->assertDatabaseCount('genre_video', 0);
    //         $this->assertDatabaseCount('cast_member_video', 0);
    //     }
    //  }

    //  /**
    //   * @test
    //   */

    //   public function uploadFilesException()
    //   {
    //     // Forca uma Excecao quando do Store de Arquivos para testar que não houve gravação efetiva no banco
    //     Event::listen(UploadFileStub::class, function () {
    //         //dd('upload files');
    //         throw new Exception('upload files');
    //     });

    //     try {
    //         $categoriesIds = Category::factory()->count(5)->create()->pluck('id')->toArray();
    //         $genresIds = Genre::factory()->count(5)->create()->pluck('id')->toArray();
    //         $castMembersIds = CastMember::factory()->count(5)->create()->pluck('id')->toArray();

    //         $sut = $this->makeSut();
    //         $input = $this->inputDTO(
    //             categories: $categoriesIds,
    //             genres: $genresIds,
    //             castMembers: $castMembersIds,
    //             videoFile: [
    //                 'name' => 'video.mp4',
    //                 'type' => 'video/mp4',
    //                 'tmp_name' => 'video.mp4',
    //                 'error' => 0,
    //             ],
    //             bannerFile: [
    //                 'name' => 'banner.png',
    //                 'type' => 'image/png',
    //                 'tmp_name' => 'banner.png',
    //                 'error' => 0,
    //             ]
    //         );

    //         $sut->exec($input);
    //         // Nao deve chegar aqui - Pois estamos testando a Exception que deve ser tratada no Catch
    //         $this->assertTrue(false);
    //     } catch (Throwable $th) {
    //         $this->assertDatabaseCount('videos', 0);
    //         $this->assertDatabaseCount('category_video', 0);
    //         $this->assertDatabaseCount('genre_video', 0);
    //         $this->assertDatabaseCount('cast_member_video', 0);
    //         $this->assertDatabaseCount('medias_video', 0);
    //         $this->assertDatabaseCount('images_video', 0);
    //     }
    // }

    // /**
    //  * @test
    //  */

    //  public function eventException()
    //  {
    //      // Forca uma Excecao quando do Disparo de Evento de Arquivos para testar que não houve gravação efetiva no banco
    //      Event::listen(VideoEventStub::class, function () {
    //         //dd('video created event');
    //         throw new Exception('video created event');
    //     });

    //     try {
    //         $categoriesIds = Category::factory()->count(5)->create()->pluck('id')->toArray();
    //         $genresIds = Genre::factory()->count(5)->create()->pluck('id')->toArray();
    //         $castMembersIds = CastMember::factory()->count(5)->create()->pluck('id')->toArray();

    //         $sut = $this->makeSut();
    //         $input = $this->inputDTO(
    //             categories: $categoriesIds,
    //             genres: $genresIds,
    //             castMembers: $castMembersIds,
    //             videoFile: [
    //                 'name' => 'video.mp4',
    //                 'type' => 'video/mp4',
    //                 'tmp_name' => 'video.mp4',
    //                 'error' => 0,
    //             ],
    //             trailerFile: [
    //                 'name' => 'trailer.mp4',
    //                 'type' => 'video/mp4',
    //                 'tmp_name' => 'trailer.mp4',
    //                 'error' => 0,
    //             ],
    //             bannerFile: [
    //                 'name' => 'banner.png',
    //                 'type' => 'image/png',
    //                 'tmp_name' => 'banner.png',
    //                 'error' => 0,
    //             ],
    //             thumbFile: [
    //                 'name' => 'thumb.png',
    //                 'type' => 'image/png',
    //                 'tmp_name' => 'thumb.png',
    //                 'error' => 0,
    //             ],
    //             thumbHalf: [
    //                 'name' => 'thumb.png',
    //                 'type' => 'image/png',
    //                 'tmp_name' => 'thumb.png',
    //                 'error' => 0,
    //             ],
    //         );

    //         $sut->exec($input);
    //         // Nao deve chegar aqui - Pois estamos testando a Exception que deve ser tratada no Catch
    //         $this->assertTrue(false);
    //     } catch (Throwable $th) {
    //         $this->assertDatabaseCount('videos', 0);
    //         $this->assertDatabaseCount('category_video', 0);
    //         $this->assertDatabaseCount('genre_video', 0);
    //         $this->assertDatabaseCount('cast_member_video', 0);
    //         $this->assertDatabaseCount('medias_video', 0);
    //         $this->assertDatabaseCount('images_video', 0);
    //     }
    //  }
}
