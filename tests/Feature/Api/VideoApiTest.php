<?php

namespace Tests\Feature\Api;

use App\Models\{
    Category as CategoryModel,
    Video as VideoModel,
    Genre as GenreModel,
    CastMember as CastMemberModel
};
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Tests\Traits\WithoutMiddlewareTrait;

class VideoApiTest extends TestCase
{
    use WithoutMiddlewareTrait;
    
    protected $endpoint = '/api/videos';

    protected $serializedFields = [
        'id',
        'title',
        'description',
        'year_launched',
        'rating',
        'duration',
        'opened',
        'created_at',
        // 'categories',
        // 'genres',
        // 'cast_members',
        // 'thumb',
        // 'banner',
        // 'trailer',
        // 'media',
        // 'thumb_half'
    ];

    public function testIndexEmpty()
    {
        $response = $this->getJson($this->endpoint);
        $response->assertStatus(Response::HTTP_OK);
    }

    public function testPaginate()
    {
        VideoModel::factory()->count(20)->create();
        
        $response = $this->getJson($this->endpoint);

        //$response->dump();

        //$response->assertStatus(Response::HTTP_OK);
        $response->assertOK();  // Equivalente a $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonCount(15, 'data');
        $response->assertJsonPath('meta.total', 20);
        $response->assertJsonPath('meta.current_page', 1);
        $response->assertJsonPath('meta.per_page', 15);
        $response->assertJsonStructure([
            'data' => [
                '*' => $this->serializedFields
            ],
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'to',
                'from',
                'per_page',
            ]
        ]);
    }

    /**
     * @test
     * @dataProvider dataProviderPagination
     */
     public function paginateWithDataProvider(
        int $total,
        int $totalCurrentPage,
        int $page = 1,
        int $totalPerPage = 15,
        string $filter = '',
     )
     {
        VideoModel::factory()->count($total)->create();

        if ($filter) {
            VideoModel::factory()->count($total)->create([
                'title' => $filter
            ]);
        }

        $params = http_build_query([
             'page' => $page,
             'totalPage' => $totalPerPage,
             'order' => 'DESC',
             'filter' => $filter,
        ]);

        $response = $this->getJson("$this->endpoint?$params");
        
        $response->assertOK();  // Equivalente a $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonPath('meta.total', $total);
        $response->assertJsonCount($totalCurrentPage, 'data');
        $response->assertJsonPath('meta.current_page', $page);
        $response->assertJsonPath('meta.per_page', $totalPerPage);
        $response->assertJsonStructure([
            'data' => [
                '*' => $this->serializedFields
            ],
            'meta' => [
                'total',
                'current_page',
                'last_page',
                'first_page',
                'to',
                'from',
                'per_page',
            ]
        ]);
     }

     protected function dataProviderPagination(): array
     {
        return [
             'test empty' => [
                 'total' => 0,
                 'totalCurrentPage' => 0,
                 'page' => 1,
                 'totalPerPage' => 15,
             ],
             'test with total two pages' => [
                 'total' => 20,
                 'totalCurrentPage' => 15,
                 'page' => 1,
                 'totalPerPage' => 15,
             ],
            'test page two' => [
                'total' => 20,
                'totalCurrentPage' => 5,
                'page' => 2,
                'totalPerPage' => 15,
            ],
             'test page four' => [
                 'total' => 40,
                 'totalCurrentPage' => 10,
                 'page' => 4,
                 'totalPerPage' => 10,
            ],
             'test with filter' => [
                 'total' => 15,
                 'totalCurrentPage' => 5,
                 'page' => 2,
                 'totalPerPage' => 10,
                 'filter' => 'test',
            ],
        ];
     }

     /**
      * @test
      */

     public function ShowNotFound()
    {
        $response = $this->getJson("$this->endpoint/0");
        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

     /**
      * @test
      */
     public function testShow()
    {
        $videoModel = VideoModel::factory()->create();
        $response = $this->getJson("$this->endpoint/$videoModel->id");

        //$response->dump();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
             'data' => $this->serializedFields
         ]);
        $this->assertEquals($videoModel->id, $response->json('data.id'));
        $this->assertEquals($videoModel->title, $response->json('data.title'));
        $this->assertEquals($videoModel->description, $response->json('data.description'));
        $this->assertEquals($videoModel->year_launched, $response->json('data.year_launched'));
        $this->assertEquals($videoModel->rating->value, $response->json('data.rating'));
        $this->assertEquals($videoModel->duration, $response->json('data.duration'));
        $this->assertEquals($videoModel->opened, $response->json('data.opened'));
        //$response->assertJson($model->toArray());
    }

     /**
      * @test
      */
     public function store()
    {
        $videoFile = UploadedFile::fake()->create('video.mp4', 1, 'video/mp4');
        $videoTrailer = UploadedFile::fake()->create('trailer.mp4', 1, 'video/mp4');
        $imageBannerFile = UploadedFile::fake()->create('banner.png', 1, 'image/png');
        $imagethumbFile = UploadedFile::fake()->create('thumb.png', 1, 'image/png');
        $imageThumbHalfFile = UploadedFile::fake()->create('thumbHalf.png', 1, 'image/png');

        $categoriesIds = CategoryModel::factory()->count(3)->create()->pluck('id')->toArray();
        $genresIds = GenreModel::factory()->count(1)->create()->pluck('id')->toArray();
        $castMembersIds = CastMemberModel::factory()->count(10)->create()->pluck('id')->toArray();

        $data = [
            'title' => 'test',
            'description' => 'test',
            'year_launched' => 2010,
            'rating' => 'L',
            'duration' => 90,
            'opened' => true,
            'categories' => $categoriesIds,
            'genres' => $genresIds,
            'cast_members' => $castMembersIds,
            //'video_file' => $videoFile,
            'trailer_file' => $videoTrailer,
            'banner_file' => $imageBannerFile,
            'thumb_file' => $imagethumbFile,
            'thumb_half_file' => $imageThumbHalfFile
        ];

        $response = $this->postJson($this->endpoint, $data);

        //$response->dump();

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'data' => $this->serializedFields
        ]);

        $this->assertEquals($data['title'], $response->json('data.title'));
        $this->assertEquals($data['description'], $response->json('data.description'));
        $this->assertEquals($data['year_launched'], $response->json('data.year_launched'));
        $this->assertEquals($data['rating'], $response->json('data.rating'));
        $this->assertEquals($data['duration'], $response->json('data.duration'));
        $this->assertEquals($data['opened'], $response->json('data.opened'));

        $this->assertEquals($categoriesIds, $response->json('data.categories'));
        $this->assertEquals($genresIds, $response->json('data.genres'));
        $this->assertEquals($castMembersIds, $response->json('data.cast_members'));
        
        $this->assertDatabaseCount('videos', 1);
        
        $this->assertDatabaseHas('videos', [
            'id' => $response->json('data.id'),
            'title' => $data['title'],
            'description' => $data['description'],
            'year_launched' => $data['year_launched'],
            'rating' => $data['rating'],
            'duration' => $data['duration'],
            'opened' => $data['opened']
        ]);

        //Storage::assertExists($response->json('data.video'));
        Storage::assertExists($response->json('data.trailer'));
        Storage::assertExists($response->json('data.banner'));
        Storage::assertExists($response->json('data.thumb'));
        Storage::assertExists($response->json('data.thumb_half'));

        Storage::deleteDirectory($response->json('data.id'));
    }

    /**
     * @test
     */
    public function storeValidationEmptyrequest()
    {
        $response = $this->postJson($this->endpoint, []);

        $response->assertUnprocessable(); // Equivalente //$response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonValidationErrors([
            'title', 
            'description', 
            'year_launched', 
            'rating', 
            'duration', 
            'opened',
            'categories', 
            'genres', 
            'cast_members'
        ]);
    }

    /**
     * @test
    */
    public function update()
    {
        $videoDb = VideoModel::factory()->create();

        $videoFile = UploadedFile::fake()->create('video.mp4', 1, 'video/mp4');
        $videoTrailer = UploadedFile::fake()->create('trailer.mp4', 1, 'video/mp4');
        $imageBannerFile = UploadedFile::fake()->create('banner.png', 1, 'image/png');
        $imagethumbFile = UploadedFile::fake()->create('thumb.png', 1, 'image/png');
        $imageThumbHalfFile = UploadedFile::fake()->create('thumbHalf.png', 1, 'image/png');

        $categoriesIds = CategoryModel::factory()->count(3)->create()->pluck('id')->toArray();
        $genresIds = GenreModel::factory()->count(1)->create()->pluck('id')->toArray();
        $castMembersIds = CastMemberModel::factory()->count(10)->create()->pluck('id')->toArray();

        $data = [
            'title' => 'Title Updated',
            'description' => 'Description Updated',
            'categories' => $categoriesIds,
            'genres' => $genresIds,
            'cast_members' => $castMembersIds,
            //'video_file' => $videoFile,
            'trailer_file' => $videoTrailer,
            'banner_file' => $imageBannerFile,
            'thumb_file' => $imagethumbFile,
            'thumb_half_file' => $imageThumbHalfFile
        ];

        $response = $this->putJson("{$this->endpoint}/{$videoDb->id}", $data);

        //$response->dump();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'data' => $this->serializedFields
        ]);

        $this->assertEquals($data['title'], $response->json('data.title'));
        $this->assertEquals($data['description'], $response->json('data.description'));

        $this->assertEquals($videoDb->year_launched, $response->json('data.year_launched'));
        $this->assertEquals($videoDb->rating->value, $response->json('data.rating'));
        $this->assertEquals($videoDb->duration, $response->json('data.duration'));
        $this->assertEquals($videoDb->opened, $response->json('data.opened'));

        $this->assertEquals($categoriesIds, $response->json('data.categories'));
        $this->assertEquals($genresIds, $response->json('data.genres'));
        $this->assertEquals($castMembersIds, $response->json('data.cast_members'));
        
        $this->assertDatabaseCount('videos', 1);
        
        $this->assertDatabaseHas('videos', [
            'id' => $response->json('data.id'),
            'title' => $data['title'],
            'description' => $data['description'],
            'year_launched' => $videoDb->year_launched,
            'rating' => $videoDb->rating->value,
            'duration' => $videoDb->duration,
            'opened' => $videoDb->opened
        ]);

        //Storage::assertExists($response->json('data.video'));
        Storage::assertExists($response->json('data.trailer'));
        Storage::assertExists($response->json('data.banner'));
        Storage::assertExists($response->json('data.thumb'));
        Storage::assertExists($response->json('data.thumb_half'));

        Storage::deleteDirectory($response->json('data.id'));
    }

    /**
     * @test
    */
    public function updateNotFound()
    {
        $videoFile = UploadedFile::fake()->create('video.mp4', 1, 'video/mp4');
        $videoTrailer = UploadedFile::fake()->create('trailer.mp4', 1, 'video/mp4');
        $imageBannerFile = UploadedFile::fake()->create('banner.png', 1, 'image/png');
        $imagethumbFile = UploadedFile::fake()->create('thumb.png', 1, 'image/png');
        $imageThumbHalfFile = UploadedFile::fake()->create('thumbHalf.png', 1, 'image/png');

        $categoriesIds = CategoryModel::factory()->count(3)->create()->pluck('id')->toArray();
        $genresIds = GenreModel::factory()->count(1)->create()->pluck('id')->toArray();
        $castMembersIds = CastMemberModel::factory()->count(10)->create()->pluck('id')->toArray();

        $data = [
            'title' => 'test',
            'description' => 'test',
            'categories' => $categoriesIds,
            'genres' => $genresIds,
            'cast_members' => $castMembersIds,
            'video_file' => $videoFile,
            'trailer_file' => $videoTrailer,
            'banner_file' => $imageBannerFile,
            'thumb_file' => $imagethumbFile,
            'thumb_half_file' => $imageThumbHalfFile
        ];

        $response = $this->putJson("{$this->endpoint}/{fake_id}", $data);

        //$response->dump();

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @test
     */
    public function testUpdateValidation()
    {
        $videoDb = VideoModel::factory()->create();


        $data = [];

        $response = $this->putJson("{$this->endpoint}/{$videoDb->id}", $data);

        //$response->dump();

        $response->assertUnprocessable(); // Equivalente //$response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonValidationErrors([
            'title', 
            'description', 
            'categories', 
            'genres', 
            'cast_members'
        ]);

    }

    /**
     * @test
     */
    public function destroyNotFound()
    {
        $response = $this->deleteJson("{$this->endpoint}/0");
        $response->assertNotFound(); // Equivale a $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * @test
     */
    public function destroy()
    {
        $videoDb = VideoModel::factory()->create();
        $response = $this->deleteJson("{$this->endpoint}/{$videoDb->id}");
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertSoftDeleted('videos', ['id' => $videoDb->id]);
    }
}
