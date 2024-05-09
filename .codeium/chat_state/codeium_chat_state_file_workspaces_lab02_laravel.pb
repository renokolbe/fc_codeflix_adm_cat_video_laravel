
È
Ê
%user-uRjMuF0VqsMZVN2RMEexLdVXp7BNQbNe’∑‰≠Ä˚Ωÿ" GoNGg31cRBM8nZcA3OYDCsDfQGFAGxdk*ä`∂Ñ
∞
Ωprivate function resolveItems(array $items): array
{
    $response = [];

    foreach ($items as $item) {
        $stdClass = new stdClass;
        foreach ($item->toArray() as $key => $value) {
            $stdClass->{$key} = $value;
        }
        array_push($response, $stdClass);
    }

    return $response;
}Ωprivate function resolveItems(array $items): array
{
    $response = [];

    foreach ($items as $item) {
        $stdClass = new stdClass;
        foreach ($item->toArray() as $key => $value) {
            $stdClass->{$key} = $value;
        }
        array_push($response, $stdClass);
    }

    return $response;
}"resolveItems*(array $items)0@8@@MHPZ    `M/workspaces/lab02-laravel/app/Repositories/Presenters/PaginationPresenter.php
ç
e
+status-3af4555c-e34e-4e47-b02e-da38ba884ae9’∑‰≠¯–ÈÆ" GoNGg31cRBM8nZcA3OYDCsDfQGFAGxdkB
$d73bb3f3-0c85-4eea-b060-f96840ce8ae6
÷
≠
(bot-d5a63194-1966-4559-a65b-8314155ed173ÿ∑‰≠©á≠K" GoNGg31cRBM8nZcA3OYDCsDfQGFAGxdk2œ1 
M/workspaces/lab02-laravel/app/Repositories/Presenters/PaginationPresenter.phpˆ@@Î
    /**;
7     * Resolve items into an array of stdClass objects.

     *8
4     * @param array $items The items to be resolved.L
H     * @return array The resolved items as an array of stdClass objects.
     */ ($d73bb3f3-0c85-4eea-b060-f96840ce8ae6
À
»
%user-yfgm1mTkNJe90Vbt1szAs3G8pVGD4c1P›˚ÉÆÄœÄê" qKXypSVC1ayhzeqUmXzj9sPafotkmoeq*Ï`óÊ
ò
∏public function testUpdate(){

    $uuid = (string) Uuid::uuid4()->toString();
    $cratedAt = '2024-01-16 22:03:00';

    $category = new Category(
        id: $uuid,
        name: 'New Category',
        description: 'New Category Description',
        isActive: true,
        createdAt: $cratedAt
    );

    $category->update(
        name: 'New Category Updated',
        description: 'New Category Description Updated',
    );

    $this->assertEquals($uuid, $category->id());
    $this->assertEquals($cratedAt, $category->createdAt());
    $this->assertEquals('New Category Updated', $category->name);
    $this->assertEquals('New Category Description Updated', $category->description);

}∏public function testUpdate(){

    $uuid = (string) Uuid::uuid4()->toString();
    $cratedAt = '2024-01-16 22:03:00';

    $category = new Category(
        id: $uuid,
        name: 'New Category',
        description: 'New Category Description',
        isActive: true,
        createdAt: $cratedAt
    );

    $category->update(
        name: 'New Category Updated',
        description: 'New Category Description Updated',
    );

    $this->assertEquals($uuid, $category->id());
    $this->assertEquals($cratedAt, $category->createdAt());
    $this->assertEquals('New Category Updated', $category->name);
    $this->assertEquals('New Category Description Updated', $category->description);

}"
testUpdate*()0:8:@QHPZ    `G/workspaces/lab02-laravel/tests/Unit/Domain/Entity/CategoryUnitTest.php
ç
e
+status-7036541f-8e16-46a5-9c8c-0a5c448b0cb0ﬁ˚ÉÆıÎß√" qKXypSVC1ayhzeqUmXzj9sPafotkmoeqB
$552dd45a-07af-40ae-b7b9-822ce484be7e
á
ﬁ
(bot-c9e3b5fd-35f2-4303-b2a8-c8120328cb74·˚ÉÆÅ±≈Ø" qKXypSVC1ayhzeqUmXzj9sPafotkmoeq2ˇ˙
G/workspaces/lab02-laravel/tests/Unit/Domain/Entity/CategoryUnitTest.php¨::°
    /**7
3     * A function to test the update functionality.

     *@
<     * @throws Some_Exception_Class description of exception
     */ ($552dd45a-07af-40ae-b7b9-822ce484be7e
Û

%user-z6AA8MlPM4jwYw6DL0vUOjc0eqGvps2NÖπõ∞¿∞É√" QBP8cwZoGOu7g5kcQ4mzcv4M1pPKp5SU*î`øé
•
Åprotected function setUp(): void
{
    parent::setUp(); 
    $this->repository = new CastMemberEloquentRepository(new Model());
}Åprotected function setUp(): void
{
    parent::setUp(); 
    $this->repository = new CastMemberEloquentRepository(new Model());
}"setUp*()08@HPZ    `b/workspaces/lab02-laravel/tests/Feature/App/Repositories/Eloquent/CastMemberEloquentRepository.php
ç
e
+status-90bb52be-5ac1-4704-b811-37ac59bfe85dÖπõ∞©Ããß" QBP8cwZoGOu7g5kcQ4mzcv4M1pPKp5SUB
$483e2e62-cdb2-4c30-85a9-0489bdfe9d99
‘
´
(bot-dbc16f78-f9f5-4883-b658-cf5dc2a9c0c2áπõ∞≥·›‡" QBP8cwZoGOu7g5kcQ4mzcv4M1pPKp5SU2Ã«
b/workspaces/lab02-laravel/tests/Feature/App/Repositories/Eloquent/CastMemberEloquentRepository.php_U
    /**9
5     * Sets up the test environment before each test.
     */ ($483e2e62-cdb2-4c30-85a9-0489bdfe9d99
ò
ï
%user-xcCsLqo6jLifnEwbmwdyPHzmZCI1VrUoâÅ¶∞¿—·#" hrl2JbRNhURFF4JxXshxOnHWvp9jh44y*∫`È¥
«
épublic function testDelete()
{
    $castMemberDB = Model::factory()->create();
    $response = $this->repository->delete($castMemberDB->id);
    $this->assertTrue($response);
    $this->assertSoftDeleted('cast_members', [
        'id' => $castMemberDB->id
    ]);
    
}épublic function testDelete()
{
    $castMemberDB = Model::factory()->create();
    $response = $this->repository->delete($castMemberDB->id);
    $this->assertTrue($response);
    $this->assertSoftDeleted('cast_members', [
        'id' => $castMemberDB->id
    ]);
    
}"
testDelete*()0ì8ì@úHPZ    `f/workspaces/lab02-laravel/tests/Feature/App/Repositories/Eloquent/CastMemberEloquentRepositoryTest.php
ç
e
+status-0c4bd101-5674-4469-8b3a-3bec45062f3eâÅ¶∞˛˝¬´" hrl2JbRNhURFF4JxXshxOnHWvp9jh44yB
$5f157269-2bd4-4b04-8fb7-4307075cae55
‡
∑
(bot-89a47976-7349-4618-8170-308a23357d0aãÅ¶∞ó◊É2" hrl2JbRNhURFF4JxXshxOnHWvp9jh44y2Ÿ‘
f/workspaces/lab02-laravel/tests/Feature/App/Repositories/Eloquent/CastMemberEloquentRepositoryTest.phphìì\
    /**4
0     * A description of the entire PHP function.

     *
     */ ($5f157269-2bd4-4b04-8fb7-4307075cae55
à
Ö
%user-PbphcN1i4cgWfgoSLKwexbbyV6GV3Y7s¶è®∞¿Ÿø‚" TXunRKTi0PvXvbil6Bbo3ERDaSIOX5bt*©`—£
ø
Œprotected function setUp(): void
{
    $this->repository = new CastMemberEloquentRepository(
        new ModelCastMember()
    );

    $this->controller = new CastMemberController();

    parent::setUp();
}Œprotected function setUp(): void
{
    $this->repository = new CastMemberEloquentRepository(
        new ModelCastMember()
    );

    $this->controller = new CastMemberController();

    parent::setUp();
}"setUp*()0 8 @)HPZ    `]/workspaces/lab02-laravel/tests/Feature/App/Http/Controllers/Api/CastMemberControllerTest.php
ç
e
+status-54a5758f-a167-4d3a-9870-89ceb82bb215¶è®∞µ∏ç‰" TXunRKTi0PvXvbil6Bbo3ERDaSIOX5btB
$856d4c0f-5a81-4232-8053-06e2947cf6b6
˘
–
(bot-8926c60d-5e8f-4783-a1e1-5d83c8c5f097®è®∞√ÈàÀ" TXunRKTi0PvXvbil6Bbo3ERDaSIOX5bt2ÒÏ
]/workspaces/lab02-laravel/tests/Feature/App/Http/Controllers/Api/CastMemberControllerTest.phpà  ~
    /**=
9     * Set up the test environment before each test case.

     *
     * @return void
     */ ($856d4c0f-5a81-4232-8053-06e2947cf6b6
®
•
%user-c6QbZBJyNoNbC4mtUEiSb1hTm3K6OxM5≈’¨∞¿Â√Ü" mnWausETXHQWzuzG6IGD69WjEwlvsrpp*…`–√
Ä
ﬁpublic function testUpdateValidationCategoriesEmpty()
{
    $genreDb = ModelGenre::factory()->create();
    $categoriesDb = ModelCategory::factory(3)->create();
    $data = [
        'name' => 'Genre Test from API',
        'categories_ids' => [],
    ];
    $response = $this->putJson("{$this->endpoint}/{$genreDb->id}", $data);
    //$response->dump();
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJsonStructure([
        'message',
        'errors' => [
            'categories_ids',
        ]
    ]);
    $this->assertArrayHasKey('categories_ids', $response->json('errors'));
    $this->assertEquals('The categories ids field is required.', $response->json('errors.categories_ids')[0]);
}ﬁpublic function testUpdateValidationCategoriesEmpty()
{
    $genreDb = ModelGenre::factory()->create();
    $categoriesDb = ModelCategory::factory(3)->create();
    $data = [
        'name' => 'Genre Test from API',
        'categories_ids' => [],
    ];
    $response = $this->putJson("{$this->endpoint}/{$genreDb->id}", $data);
    //$response->dump();
    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    $response->assertJsonStructure([
        'message',
        'errors' => [
            'categories_ids',
        ]
    ]);
    $this->assertArrayHasKey('categories_ids', $response->json('errors'));
    $this->assertEquals('The categories ids field is required.', $response->json('errors.categories_ids')[0]);
}"#testUpdateValidationCategoriesEmpty*()0î8î@ßHPZ    `</workspaces/lab02-laravel/tests/Feature/Api/GenreApiTest.php
ç
e
+status-82414ee7-4a16-49e7-8733-f3fdb35acb2c∆’¨∞¬≈©Ä" mnWausETXHQWzuzG6IGD69WjEwlvsrppB
$4a992cfd-a410-4fdd-a1c1-0075585fa817
»
ü
(bot-e755c9ad-ef2d-4b77-b264-6d85c5699ec8«’¨∞ˆ’¶±" mnWausETXHQWzuzG6IGD69WjEwlvsrpp2¿ª
</workspaces/lab02-laravel/tests/Feature/Api/GenreApiTest.phpyîîm
    /**Q
M     * A test function to validate the behavior when categories_ids is empty.
     */ ($4a992cfd-a410-4fdd-a1c1-0075585fa817
ä
á
%user-5EnoayUDoYJeH7g3ZWTSMUrElzhf6c6w©≤≤∞¿ÈÈÎ" jh2KNOiLedaOPmNBegyTkA4o5AmQnnEN*´`¢B•
4Undefined function 'Tests\Unit\Domain\Entity\Image'.
Image˜ ˜(é    {

        $video = new Video(
            title: 'New Title',
            description: 'New Title Description',
            yearLaunched: 2024,
            duration: 98,
            opened: true,
            rating: Rating::ER,
            published: true,
            thumbFile: Image(
                path: 'caminho/para/imagem.png'
            ),
        );

        $this->assertNotEmpty($video->id());
        $this->assertNotEmpty($video->createdAt());
        $this->assertEquals(true, $video->published);
    }
}
 *D/workspaces/lab02-laravel/tests/Unit/Domain/Entity/VideoUnitTest.php0˜
í
È
+status-917b6a94-f20e-4155-ab98-b58374c522e6™≤≤∞€’™¢" jh2KNOiLedaOPmNBegyTkA4o5AmQnnENBá
Ñ4Undefined function 'Tests\Unit\Domain\Entity\Image'
PHP function not found
Image class not defined
Video class definition
$e800aef1-3d83-4db6-bb91-f1eda5691b1e
√
ö
(bot-2542ba6d-c7c4-473e-a84a-589b02434b5dÆ≤≤∞€∑∆£" jh2KNOiLedaOPmNBegyTkA4o5AmQnnEN2ªç"‹
D/workspaces/lab02-laravel/tests/Unit/Domain/Entity/VideoUnitTest.phpG
/workspaces/lab02-laravel*tests/Unit/Domain/Entity/VideoUnitTest.phptestValueObjectImage"Tests\Unit\Domain\Entity"VideoUnitTest(Ï0ˇ8P`h"ú
D/workspaces/lab02-laravel/tests/Unit/Domain/Entity/VideoUnitTest.phpG
/workspaces/lab02-laravel*tests/Unit/Domain/Entity/VideoUnitTest.php<?php08P"√
D/workspaces/lab02-laravel/tests/Unit/Domain/Entity/VideoUnitTest.phpG
/workspaces/lab02-laravel*tests/Unit/Domain/Entity/VideoUnitTest.phpVideoUnitTest"Tests\Unit\Domain\Entity(0Ä8Ph"œ
D/workspaces/lab02-laravel/tests/Unit/Domain/Entity/VideoUnitTest.phpG
/workspaces/lab02-laravel*tests/Unit/Domain/Entity/VideoUnitTest.php	testNewId"Tests\Unit\Domain\Entity"VideoUnitTest()098P`h"£
G/workspaces/lab02-laravel/tests/Unit/Domain/Entity/CategoryUnitTest.phpK
/workspaces/lab02-laravel/-tests/Unit/Domain/Entity/CategoryUnitTest.php<?php0	8P"‘
D/workspaces/lab02-laravel/tests/Unit/Domain/Entity/VideoUnitTest.phpG
/workspaces/lab02-laravel*tests/Unit/Domain/Entity/VideoUnitTest.phptestAttributes"Tests\Unit\Domain\Entity"VideoUnitTest(0'8P`h"ú
D/workspaces/lab02-laravel/tests/Unit/Domain/Entity/GenreUnitTest.phpG
/workspaces/lab02-laravel*tests/Unit/Domain/Entity/GenreUnitTest.php<?php08P"’
D/workspaces/lab02-laravel/tests/Unit/Domain/Entity/VideoUnitTest.phpG
/workspaces/lab02-laravel*tests/Unit/Domain/Entity/VideoUnitTest.phptestAddGenreId"Tests\Unit\Domain\Entity"VideoUnitTest(v0å8P`h"◊
D/workspaces/lab02-laravel/tests/Unit/Domain/Entity/VideoUnitTest.phpG
/workspaces/lab02-laravel*tests/Unit/Domain/Entity/VideoUnitTest.phptestAddCategoryId"Tests\Unit\Domain\Entity"VideoUnitTest(;0Q8P`h"€
D/workspaces/lab02-laravel/tests/Unit/Domain/Entity/VideoUnitTest.phpG
/workspaces/lab02-laravel*tests/Unit/Domain/Entity/VideoUnitTest.phptestAddCastMemberId"Tests\Unit\Domain\Entity"VideoUnitTest(±0«8P`h"Ÿ
D/workspaces/lab02-laravel/tests/Unit/Domain/Entity/VideoUnitTest.phpG
/workspaces/lab02-laravel*tests/Unit/Domain/Entity/VideoUnitTest.phptestRemoveGenreId"Tests\Unit\Domain\Entity"VideoUnitTest(é0Ø8P`h"ù
D/workspaces/lab02-laravel/tests/Unit/App/Models/CategoryUnitTest.phpH
/workspaces/lab02-laravel/*tests/Unit/App/Models/CategoryUnitTest.php<?php08P"⁄
D/workspaces/lab02-laravel/tests/Unit/Domain/Entity/VideoUnitTest.phpG
/workspaces/lab02-laravel*tests/Unit/Domain/Entity/VideoUnitTest.phptestRemoveCategoryId"Tests\Unit\Domain\Entity"VideoUnitTest(S0t8P`h
ö
óThe issue "Undefined function 'Tests\Unit\Domain\Entity\Image'" occurs because `Image` is being used as a function, but it is actually a class. To fix this, you should instantiate the `Image` class by using the `new` keyword, for example:

```php
$thumbFile = new Image(
    path: 'caminho/para/imagem.png'
);

$video = new Video(
    title: 'New Title',
    description: 'New Title Description',
    yearLaunched: 2024,
    duration: 98,
    opened: true,
    rating: Rating::ER,
    published: true,
    thumbFile: $thumbFile,
);
```$e800aef1-3d83-4db6-bb91-f1eda5691b1e
÷	
”	
%user-eUITdpvaE7AtNaXdeqkN0yp2yARWSeOQ∫ﬁ¿∞Ä√é‡" jxvplTJagNXbiO7bxm4LKS01QXA9XkFe*˜`ÖÒ
≠
ïpublic function __construct(
    protected Uuid|string $id = '',
    protected string $name = '',
    protected string $description = '',
    protected bool $isActive = true,
    protected DateTime|string $createdAt = ''
){
    $this->id = $this->id ? new Uuid($this->id) : Uuid::random();
    $this->createdAt = $this->createdAt ? new DateTime($this->createdAt) : new DateTime();
    $this->validate();
}ïpublic function __construct(
    protected Uuid|string $id = '',
    protected string $name = '',
    protected string $description = '',
    protected bool $isActive = true,
    protected DateTime|string $createdAt = ''
){
    $this->id = $this->id ? new Uuid($this->id) : Uuid::random();
    $this->createdAt = $this->createdAt ? new DateTime($this->createdAt) : new DateTime();
    $this->validate();
}"__construct*€(
        protected Uuid|string $id = '',
        protected string $name = '',
        protected string $description = '',
        protected bool $isActive = true,
        protected DateTime|string $createdAt = ''
    )08@HPZ    `=/workspaces/lab02-laravel/src/Core/Domain/Entity/Category.php
ç
e
+status-2b000ad8-fef1-4aa2-beed-4022bb892346∫ﬁ¿∞õµœÆ" jxvplTJagNXbiO7bxm4LKS01QXA9XkFeB
$df319581-39fd-44d7-b4fe-db4f4c3f1272
›
¥
(bot-b8130096-ff8c-4994-a864-41cc95e3e5fbºﬁ¿∞´Ç˜º" jxvplTJagNXbiO7bxm4LKS01QXA9XkFe2’R–
=/workspaces/lab02-laravel/src/Core/Domain/Entity/Category.phpåÅ
    /**2
.     * Constructs a new instance of the class.

     *"
     * @param Uuid|string $id 
     * @param string $name &
"     * @param string $description !
     * @param bool $isActive -
)     * @param DateTime|string $createdAt @
<     * @throws Some_Exception_Class description of exception$
      * @return Some_Return_Value
     */ ($df319581-39fd-44d7-b4fe-db4f4c3f1272
∏
µ
%user-Jps7uZw28krNOekp6fT2jp0EFtslogAa≠ªÖ±¿˙Ø∑" hE45OSNEw0oTplsTTrNiwfS92uQwFFfa*Ÿ`ê”
˚
Âpublic function testExecInputOutput()
{
    $this->createUseCase();

    $response = $this->useCase->exec(
        input: $this->createMockInputDTO(),
    );

    $this->assertInstanceOf(UpdateOutputVideoDTO::class, $response);
}Âpublic function testExecInputOutput()
{
    $this->createUseCase();

    $response = $this->useCase->exec(
        input: $this->createMockInputDTO(),
    );

    $this->assertInstanceOf(UpdateOutputVideoDTO::class, $response);
}"testExecInputOutput*()08@HPZ    `Q/workspaces/lab02-laravel/tests/Unit/UseCase/Video/UpdateVideoUseCaseUnitTest.php
˚
“
+status-b87db56e-66b8-4454-81ca-7fa69c68e8f8ÆªÖ±Õó¨˛" hE45OSNEw0oTplsTTrNiwfS92uQwFFfaBq
otestExecInputOutput()
*A description of the entire PHP function.
'@param datatype $paramname description
$47d86d9b-cdb6-42e2-ac16-c5afdeaf1d58
˚
“
(bot-0d1f5e3c-d76d-4eb5-a265-da0e4fd86ace±ªÖ±ÉüÙº" hE45OSNEw0oTplsTTrNiwfS92uQwFFfa2Û""Ä
Q/workspaces/lab02-laravel/tests/Unit/UseCase/Video/UpdateVideoUseCaseUnitTest.phpT
/workspaces/lab02-laravel7tests/Unit/UseCase/Video/UpdateVideoUseCaseUnitTest.phptestExecInputOutput"Tests\Unit\UseCase\Video"UpdateVideoUseCaseUnitTest(08P`h"Ä
Q/workspaces/lab02-laravel/tests/Unit/UseCase/Video/CreateVideoUseCaseUnitTest.phpT
/workspaces/lab02-laravel7tests/Unit/UseCase/Video/CreateVideoUseCaseUnitTest.phptestExecInputOutput"Tests\Unit\UseCase\Video"CreateVideoUseCaseUnitTest(08P`h"‹
N/workspaces/lab02-laravel/src/Core/UseCase/Video/Update/UpdateVideoUseCase.phpQ
/workspaces/lab02-laravel4src/Core/UseCase/Video/Update/UpdateVideoUseCase.phpUpdateVideoUseCase"Core\UseCase\Video\Update(0\8Ph"›
N/workspaces/lab02-laravel/src/Core/UseCase/Video/Create/CreateVideoUseCase.phpQ
/workspaces/lab02-laravel4src/Core/UseCase/Video/Create/CreateVideoUseCase.phpCreateVideoUseCase"Core\UseCase\Video\Create(0ô8Ph"È
Q/workspaces/lab02-laravel/tests/Unit/UseCase/Video/UpdateVideoUseCaseUnitTest.phpT
/workspaces/lab02-laravel7tests/Unit/UseCase/Video/UpdateVideoUseCaseUnitTest.phpUpdateVideoUseCaseUnitTest"Tests\Unit\UseCase\Video(0<8Ph"È
Q/workspaces/lab02-laravel/tests/Unit/UseCase/Video/CreateVideoUseCaseUnitTest.phpT
/workspaces/lab02-laravel7tests/Unit/UseCase/Video/CreateVideoUseCaseUnitTest.phpCreateVideoUseCaseUnitTest"Tests\Unit\UseCase\Video(0>8Ph"‰
N/workspaces/lab02-laravel/src/Core/UseCase/Video/Update/UpdateVideoUseCase.phpQ
/workspaces/lab02-laravel4src/Core/UseCase/Video/Update/UpdateVideoUseCase.phpexec"Core\UseCase\Video\Update"UpdateVideoUseCase(0C8P`h"‡
U/workspaces/lab02-laravel/tests/Unit/UseCase/Video/CreateVideoUseCaseUnitTest.php.oriY
/workspaces/lab02-laravel/;tests/Unit/UseCase/Video/CreateVideoUseCaseUnitTest.php.ori$private function createMockInputDTO((Ÿ0·8"Ω
U/workspaces/lab02-laravel/tests/Unit/UseCase/Video/CreateVideoUseCaseUnitTest.php.oriY
/workspaces/lab02-laravel/;tests/Unit/UseCase/Video/CreateVideoUseCaseUnitTest.php.ori{(õ0¿8"‰
N/workspaces/lab02-laravel/src/Core/UseCase/Video/Create/CreateVideoUseCase.phpQ
/workspaces/lab02-laravel4src/Core/UseCase/Video/Create/CreateVideoUseCase.phpexec"Core\UseCase\Video\Create"CreateVideoUseCase(*0M8P`h"∂
Q/workspaces/lab02-laravel/tests/Unit/UseCase/Video/CreateVideoUseCaseUnitTest.phpT
/workspaces/lab02-laravel7tests/Unit/UseCase/Video/CreateVideoUseCaseUnitTest.php<?php08P"º
T/workspaces/lab02-laravel/src/Core/UseCase/Video/Create/DTO/CreateOutputVideoDTO.phpW
/workspaces/lab02-laravel:src/Core/UseCase/Video/Create/DTO/CreateOutputVideoDTO.php<?php08PË
Q/workspaces/lab02-laravel/tests/Unit/UseCase/Video/UpdateVideoUseCaseUnitTest.phpêÖ
    /**D
@     * Test the execution of the use case with input and output.

     *
     * @return void
     */ ($47d86d9b-cdb6-42e2-ac16-c5afdeaf1d58
ê(
ç(
%user-261zG7b836SOU639WFLxFno96fMVQovv±ªÖ±¿ÇÛá" EFttYLax8ciqKparpMtXZhHHiEVpbeiz*±'`ıR´'
”&
¬	class UpdateVideoUseCaseUnitTest extends BaseVideoUseCaseUnitTest
{
    public function testExecInputOutput()
    {
        $this->createUseCase();

        $response = $this->useCase->exec(
            input: $this->createMockInputDTO(),
        );

        $this->assertInstanceOf(UpdateOutputVideoDTO::class, $response);
    }

    protected function nameActionRepository(): string
    {
        return 'update';
    }
    protected function getUseCase(): string
    {
        return UpdateVideoUseCase::class;
    }

    protected function createMockInputDTO(
        array $categoriesIds = [], 
        array $genresIds = [], 
        array $castMembersIds = [],
        ?array $videoFile = null,
        ?array $trailerFile = null,
        ?array $thumbFile = null,
        ?array $thumbHalf = null,
        ?array $bannerFile = null
    ){
        return Mockery::mock(UpdateInputVideoDTO::class, [
            Uuid::random(),
            'Video title',
            'Video description',
            $categoriesIds,
            $genresIds,
            $castMembersIds,
            $videoFile,
            $trailerFile,
            $thumbFile,
            $thumbHalf,
            $bannerFile,
        ]);
    }

}<(2¬	class UpdateVideoUseCaseUnitTest extends BaseVideoUseCaseUnitTest
{
    public function testExecInputOutput()
    {
        $this->createUseCase();

        $response = $this->useCase->exec(
            input: $this->createMockInputDTO(),
        );

        $this->assertInstanceOf(UpdateOutputVideoDTO::class, $response);
    }

    protected function nameActionRepository(): string
    {
        return 'update';
    }
    protected function getUseCase(): string
    {
        return UpdateVideoUseCase::class;
    }

    protected function createMockInputDTO(
        array $categoriesIds = [], 
        array $genresIds = [], 
        array $castMembersIds = [],
        ?array $videoFile = null,
        ?array $trailerFile = null,
        ?array $thumbFile = null,
        ?array $thumbHalf = null,
        ?array $bannerFile = null
    ){
        return Mockery::mock(UpdateInputVideoDTO::class, [
            Uuid::random(),
            'Video title',
            'Video description',
            $categoriesIds,
            $genresIds,
            $castMembersIds,
            $videoFile,
            $trailerFile,
            $thumbFile,
            $thumbHalf,
            $bannerFile,
        ]);
    }

}JUpdateVideoUseCaseUnitTestR˚
Âpublic function testExecInputOutput()
{
    $this->createUseCase();

    $response = $this->useCase->exec(
        input: $this->createMockInputDTO(),
    );

    $this->assertInstanceOf(UpdateOutputVideoDTO::class, $response);
}Âpublic function testExecInputOutput()
{
    $this->createUseCase();

    $response = $this->useCase->exec(
        input: $this->createMockInputDTO(),
    );

    $this->assertInstanceOf(UpdateOutputVideoDTO::class, $response);
}"testExecInputOutput*()08@HPZ    `Rƒ
Jprotected function nameActionRepository(): string
{
    return 'update';
}Jprotected function nameActionRepository(): string
{
    return 'update';
}"nameActionRepository*()08@HPZ    `R»
Qprotected function getUseCase(): string
{
    return UpdateVideoUseCase::class;
}Qprotected function getUseCase(): string
{
    return UpdateVideoUseCase::class;
}"
getUseCase*()08@!HPZ    `Rˆ
÷protected function createMockInputDTO(
    array $categoriesIds = [], 
    array $genresIds = [], 
    array $castMembersIds = [],
    ?array $videoFile = null,
    ?array $trailerFile = null,
    ?array $thumbFile = null,
    ?array $thumbHalf = null,
    ?array $bannerFile = null
){
    return Mockery::mock(UpdateInputVideoDTO::class, [
        Uuid::random(),
        'Video title',
        'Video description',
        $categoriesIds,
        $genresIds,
        $castMembersIds,
        $videoFile,
        $trailerFile,
        $thumbFile,
        $thumbHalf,
        $bannerFile,
    ]);
}÷protected function createMockInputDTO(
    array $categoriesIds = [], 
    array $genresIds = [], 
    array $castMembersIds = [],
    ?array $videoFile = null,
    ?array $trailerFile = null,
    ?array $thumbFile = null,
    ?array $thumbHalf = null,
    ?array $bannerFile = null
){
    return Mockery::mock(UpdateInputVideoDTO::class, [
        Uuid::random(),
        'Video title',
        'Video description',
        $categoriesIds,
        $genresIds,
        $castMembersIds,
        $videoFile,
        $trailerFile,
        $thumbFile,
        $thumbHalf,
        $bannerFile,
    ]);
}"createMockInputDTO*õ(
        array $categoriesIds = [], 
        array $genresIds = [], 
        array $castMembersIds = [],
        ?array $videoFile = null,
        ?array $trailerFile = null,
        ?array $thumbFile = null,
        ?array $thumbHalf = null,
        ?array $bannerFile = null
    )0#8#@:HPZ    `ZTests\Unit\UseCase\VideohpQ/workspaces/lab02-laravel/tests/Unit/UseCase/Video/UpdateVideoUseCaseUnitTest.php
¿
ó
+status-41c6c758-d3b5-48b3-9dbd-aca6efdd72fe≤ªÖ±è–ÃW" EFttYLax8ciqKparpMtXZhHHiEVpbeizB∂
≥,UpdateVideoUseCaseUnitTest class definition
?testExecInputOutput method in UpdateVideoUseCaseUnitTest class
@nameActionRepository method in UpdateVideoUseCaseUnitTest class
$9fd47dc3-77fd-4681-917c-21c3540b4c94
ã
‚
(bot-4c421eb8-ac16-4512-8b09-29274b5973ca∂ªÖ±Ï˜¶ö" EFttYLax8ciqKparpMtXZhHHiEVpbeiz2É›"È
Q/workspaces/lab02-laravel/tests/Unit/UseCase/Video/UpdateVideoUseCaseUnitTest.phpT
/workspaces/lab02-laravel7tests/Unit/UseCase/Video/UpdateVideoUseCaseUnitTest.phpUpdateVideoUseCaseUnitTest"Tests\Unit\UseCase\Video(0<8Ph"Ä
Q/workspaces/lab02-laravel/tests/Unit/UseCase/Video/UpdateVideoUseCaseUnitTest.phpT
/workspaces/lab02-laravel7tests/Unit/UseCase/Video/UpdateVideoUseCaseUnitTest.phptestExecInputOutput"Tests\Unit\UseCase\Video"UpdateVideoUseCaseUnitTest(08P`h"Å
Q/workspaces/lab02-laravel/tests/Unit/UseCase/Video/UpdateVideoUseCaseUnitTest.phpT
/workspaces/lab02-laravel7tests/Unit/UseCase/Video/UpdateVideoUseCaseUnitTest.phpnameActionRepository"Tests\Unit\UseCase\Video"UpdateVideoUseCaseUnitTest(08P`h"∏
U/workspaces/lab02-laravel/tests/Unit/UseCase/Video/CreateVideoUseCaseUnitTest.php.oriY
/workspaces/lab02-laravel/;tests/Unit/UseCase/Video/CreateVideoUseCaseUnitTest.php.ori(0:8"ˇ
Q/workspaces/lab02-laravel/tests/Unit/UseCase/Video/UpdateVideoUseCaseUnitTest.phpT
/workspaces/lab02-laravel7tests/Unit/UseCase/Video/UpdateVideoUseCaseUnitTest.phpcreateMockInputDTO"Tests\Unit\UseCase\Video"UpdateVideoUseCaseUnitTest(#0:8P`h"È
Q/workspaces/lab02-laravel/tests/Unit/UseCase/Video/CreateVideoUseCaseUnitTest.phpT
/workspaces/lab02-laravel7tests/Unit/UseCase/Video/CreateVideoUseCaseUnitTest.phpCreateVideoUseCaseUnitTest"Tests\Unit\UseCase\Video(0>8Ph"∂
Q/workspaces/lab02-laravel/tests/Unit/UseCase/Video/UpdateVideoUseCaseUnitTest.phpT
/workspaces/lab02-laravel7tests/Unit/UseCase/Video/UpdateVideoUseCaseUnitTest.php<?php08P"∂
Q/workspaces/lab02-laravel/tests/Unit/UseCase/Video/CreateVideoUseCaseUnitTest.phpT
/workspaces/lab02-laravel7tests/Unit/UseCase/Video/CreateVideoUseCaseUnitTest.php<?php08P"‰
O/workspaces/lab02-laravel/tests/Unit/UseCase/Video/BaseVideoUseCaseUnitTest.phpR
/workspaces/lab02-laravel5tests/Unit/UseCase/Video/BaseVideoUseCaseUnitTest.phpBaseVideoUseCaseUnitTest"Tests\Unit\UseCase\Video(0«8Ph
à
ÖThe `UpdateVideoUseCaseUnitTest` class is a unit test class for the `UpdateVideoUseCase` class. It extends the `BaseVideoUseCaseUnitTest` class.

The `testExecInputOutput` method is a test case that creates an instance of the `UpdateVideoUseCase` class, executes the `exec` method with a mock input DTO, and asserts that the response is an instance of the `UpdateOutputVideoDTO` class.

The `nameActionRepository` method returns the name of the repository action, which is 'update'.

The `getUseCase` method returns the fully qualified class name of the `UpdateVideoUseCase` class.

The `createMockInputDTO` method creates a mock instance of the `UpdateInputVideoDTO` class with some default values for the constructor parameters.

In summary, this class is a unit test for the `UpdateVideoUseCase` class, providing test cases for the `exec` method and mocking the necessary dependencies for the test.$9fd47dc3-77fd-4681-917c-21c3540b4c94
Ó
Î
%user-KviEaC3kwAJRQXZWlXNhat9glNHHGSs4ø˛≥±¿›Ó¡" 6hECErzg57QlOAl1xmoQ9zsOGoo6Vrhj*è`â
µ
ºpublic function execute(ListInputVideoDTO $input): ListOutputVideoDTO
{
    dump($input->id);
    $entity = $this->videoRepository->findById($input->id);

    return new ListOutputVideoDTO(
        id: $entity->id,
        title: $entity->title,
        description: $entity->description,
        yearLaunched: $entity->yearLaunched,
        duration: $entity->duration,
        opened: $entity->opened,
        rating: $entity->rating,
        categories: $entity->categoriesId,
        genres: $entity->genresId,
        castMembers: $entity->castMembersIds,
        videoFile: $entity->videoFile()?->filePath,
        trailerFile: $entity->trailerFile()?->filePath,
        thumbFile: $entity->thumbFile()?->path(),
        thumbHalf: $entity->thumbHalf()?->path(),
        bannerFile: $entity->bannerFile()?->path()
    );
}ºpublic function execute(ListInputVideoDTO $input): ListOutputVideoDTO
{
    dump($input->id);
    $entity = $this->videoRepository->findById($input->id);

    return new ListOutputVideoDTO(
        id: $entity->id,
        title: $entity->title,
        description: $entity->description,
        yearLaunched: $entity->yearLaunched,
        duration: $entity->duration,
        opened: $entity->opened,
        rating: $entity->rating,
        categories: $entity->categoriesId,
        genres: $entity->genresId,
        castMembers: $entity->castMembersIds,
        videoFile: $entity->videoFile()?->filePath,
        trailerFile: $entity->trailerFile()?->filePath,
        thumbFile: $entity->thumbFile()?->path(),
        thumbHalf: $entity->thumbHalf()?->path(),
        bannerFile: $entity->bannerFile()?->path()
    );
}"execute*(ListInputVideoDTO $input)08@&HPZ    `M/workspaces/lab02-laravel/.vscode-server/data/User/History/-72967930/10um.php
÷
≠
+status-5f6390b9-8447-470d-a587-98c81a716ef8¿˛≥±Í∑ÓÃ" 6hECErzg57QlOAl1xmoQ9zsOGoo6VrhjBL
JFpublic function execute(ListInputVideoDTO $input): ListOutputVideoDTO
$4a9554e7-ffd2-42ce-b111-ac527160bed8
≤
â
(bot-f64c5db4-8b50-4132-a6bf-88a617c8e5b4≈˛≥±Û…≤Œ" 6hECErzg57QlOAl1xmoQ9zsOGoo6Vrhj2™M"·
M/workspaces/lab02-laravel/.vscode-server/data/User/History/-72967930/10um.phpP
/workspaces/lab02-laravel3.vscode-server/data/User/History/-72967930/10um.phpexecute"Core\UseCase\Video\List"ListVideoUseCase(0&8P`h"≥
O/workspaces/lab02-laravel/src/Core/UseCase/Video/List/DTO/ListInputVideoDTO.phpS
/workspaces/lab02-laravel/5src/Core/UseCase/Video/List/DTO/ListInputVideoDTO.php<?php08P"±
N/workspaces/lab02-laravel/src/Core/UseCase/Video/Delete/DeleteVideoUseCase.phpR
/workspaces/lab02-laravel/4src/Core/UseCase/Video/Delete/DeleteVideoUseCase.php<?php0	8P"≥
O/workspaces/lab02-laravel/src/Core/UseCase/Video/Paginate/ListVideosUseCase.phpS
/workspaces/lab02-laravel/5src/Core/UseCase/Video/Paginate/ListVideosUseCase.php<?php0	8P"÷
M/workspaces/lab02-laravel/.vscode-server/data/User/History/-72967930/10um.phpP
/workspaces/lab02-laravel3.vscode-server/data/User/History/-72967930/10um.phpListVideoUseCase"Core\UseCase\Video\List(
0'8Ph"±
N/workspaces/lab02-laravel/src/Core/UseCase/Video/Create/CreateVideoUseCase.phpR
/workspaces/lab02-laravel/4src/Core/UseCase/Video/Create/CreateVideoUseCase.php<?php08P"ª
S/workspaces/lab02-laravel/src/Core/UseCase/Video/Update/DTO/UpdateInputVideoDTO.phpW
/workspaces/lab02-laravel/9src/Core/UseCase/Video/Update/DTO/UpdateInputVideoDTO.php<?php08P"ª
S/workspaces/lab02-laravel/src/Core/UseCase/Video/Delete/DTO/DeleteInputVideoDTO.phpW
/workspaces/lab02-laravel/9src/Core/UseCase/Video/Delete/DTO/DeleteInputVideoDTO.php<?php08P"∞
N/workspaces/lab02-laravel/src/Core/UseCase/Video/Update/UpdateVideoUseCase.phpQ
/workspaces/lab02-laravel4src/Core/UseCase/Video/Update/UpdateVideoUseCase.php<?php08P"\
M/workspaces/lab02-laravel/.vscode-server/data/User/History/-72967930/10um.php<?php0	8P"Ω
T/workspaces/lab02-laravel/src/Core/UseCase/Video/Delete/DTO/DeleteOutputVideoDTO.phpX
/workspaces/lab02-laravel/:src/Core/UseCase/Video/Delete/DTO/DeleteOutputVideoDTO.php<?php08P"ª
S/workspaces/lab02-laravel/src/Core/UseCase/Video/Create/DTO/CreateInputVideoDTO.phpW
/workspaces/lab02-laravel/9src/Core/UseCase/Video/Create/DTO/CreateInputVideoDTO.php<?php08P"≥
O/workspaces/lab02-laravel/tests/Unit/UseCase/Video/ListVideoUseCaseUnitTest.phpS
/workspaces/lab02-laravel/5tests/Unit/UseCase/Video/ListVideoUseCaseUnitTest.php<?php08P"Ç
N/workspaces/lab02-laravel/src/Core/UseCase/Video/Delete/DeleteVideoUseCase.phpl
/workspaces/lab02-laravel/N/workspaces/lab02-laravel/src/Core/UseCase/Video/Delete/DeleteVideoUseCase.phpexecute"Core\UseCase\Video\Delete"DeleteVideoUseCase(08P`h"˙
O/workspaces/lab02-laravel/src/Core/UseCase/Video/List/DTO/ListInputVideoDTO.phpm
/workspaces/lab02-laravel/O/workspaces/lab02-laravel/src/Core/UseCase/Video/List/DTO/ListInputVideoDTO.phpListInputVideoDTO"Core\UseCase\Video\List\DTO(0	8Ph"â
O/workspaces/lab02-laravel/src/Core/UseCase/Video/List/DTO/ListInputVideoDTO.phpm
/workspaces/lab02-laravel/O/workspaces/lab02-laravel/src/Core/UseCase/Video/List/DTO/ListInputVideoDTO.php__construct"Core\UseCase\Video\List\DTO"ListInputVideoDTO(08P`h≈
M/workspaces/lab02-laravel/.vscode-server/data/User/History/-72967930/10um.phpÒÊ
    /**W
S     * Executes the ListVideoUseCase by fetching the entity based on the input ID, F
B     * and returns a ListOutputVideoDTO with detailed information.

     *R
N     * @param ListInputVideoDTO $input The input data for the ListVideoUseCaseI
E     * @return ListOutputVideoDTO The output data with entity details
     */ ($4a9554e7-ffd2-42ce-b111-ac527160bed8
æ
ª
%user-O5wjYpHvyBAQPEWHEqQz4vWkrFvbsVS6Î†¥±¿ƒ¡=" rcqaOltl8TDdtvmKvwLTk0wfkxStN10w*‡`Ã⁄
˛
•public function uploadFilesException()
{
  // Forca uma Excecao quando do Store de Arquivos para testar que n√£o houve grava√ß√£o efetiva no banco
  Event::listen(UploadFileStub::class, function () {
      //dd('upload files');
      throw new Exception('upload files');
  });

  try {
      $sut = $this->makeSut();
      $input = $this->inputDTO(
          videoFile: [
              'name' => 'video.mp4',
              'type' => 'video/mp4',
              'tmp_name' => 'video.mp4',
              'error' => 0,
              'size' => 0
          ]
          );

      $sut->exec($input);
      // Nao deve chegar aqui - Pois estamos testando a Exception que deve ser tratada no Catch
      $this->assertTrue(false);
  } catch (Throwable $th) {
      $this->assertDatabaseCount('videos', 0);
  }
    }•public function uploadFilesException()
{
  // Forca uma Excecao quando do Store de Arquivos para testar que n√£o houve grava√ß√£o efetiva no banco
  Event::listen(UploadFileStub::class, function () {
      //dd('upload files');
      throw new Exception('upload files');
  });

  try {
      $sut = $this->makeSut();
      $input = $this->inputDTO(
          videoFile: [
              'name' => 'video.mp4',
              'type' => 'video/mp4',
              'tmp_name' => 'video.mp4',
              'error' => 0,
              'size' => 0
          ]
          );

      $sut->exec($input);
      // Nao deve chegar aqui - Pois estamos testando a Exception que deve ser tratada no Catch
      $this->assertTrue(false);
  } catch (Throwable $th) {
      $this->assertDatabaseCount('videos', 0);
  }
    }"uploadFilesException*()0O8O@iHPZ      `U/workspaces/lab02-laravel/tests/Feature/Core/Usecase/Video/CreateVideoUseCaseTest.php
†
˜
+status-3f733831-2cb0-4292-be97-1e1976c240c8Ï†¥±ÜÊ˘|" rcqaOltl8TDdtvmKvwLTk0wfkxStN10wBñ
ì'public function uploadFilesException()
f/**
  * A description of the entire PHP function.
  *
  * @throws Exception when uploading files
  */
$ab6a277f-9b97-40e5-89c7-7195a9f70bd0
°
¯
(bot-18471e5f-b89a-4c67-8693-52109e41c4c5Ò†¥±ƒÃ¢¢" rcqaOltl8TDdtvmKvwLTk0wfkxStN10w2ôI"ç
U/workspaces/lab02-laravel/tests/Feature/Core/Usecase/Video/CreateVideoUseCaseTest.phpX
/workspaces/lab02-laravel;tests/Feature/Core/Usecase/Video/CreateVideoUseCaseTest.phpuploadFilesException" Tests\Feature\Core\Usecase\Video"CreateVideoUseCaseTest(O0i8P`h"}
4/workspaces/lab02-laravel/app/Exceptions/Handler.php8
/workspaces/lab02-laravel/app/Exceptions/Handler.php<?php0
8P"∑
Q/workspaces/lab02-laravel/src/Core/Domain/Exception/EntityValidationException.phpU
/workspaces/lab02-laravel/7src/Core/Domain/Exception/EntityValidationException.php<?php08P"i
*/workspaces/lab02-laravel/lang/en/auth.php.
/workspaces/lab02-laravel/lang/en/auth.php<?php08P"ß
I/workspaces/lab02-laravel/src/Core/Domain/Exception/NotFoundException.phpM
/workspaces/lab02-laravel//src/Core/Domain/Exception/NotFoundException.php<?php08P"f
)/workspaces/lab02-laravel/config/auth.php-
/workspaces/lab02-laravel/config/auth.php];(n0o8P"h
*/workspaces/lab02-laravel/config/queue.php.
/workspaces/lab02-laravel/config/queue.php];(\0]8P"h
*/workspaces/lab02-laravel/config/cache.php.
/workspaces/lab02-laravel/config/cache.php];(m0n8P"ù
D/workspaces/lab02-laravel/tests/Unit/Domain/Entity/VideoUnitTest.phpH
/workspaces/lab02-laravel/*tests/Unit/Domain/Entity/VideoUnitTest.php<?php08P"Õ
\/workspaces/lab02-laravel/tests/Unit/App/Http/Controllers/Api/CategoryControllerUnitTest.php`
/workspaces/lab02-laravel/Btests/Unit/App/Http/Controllers/Api/CategoryControllerUnitTest.php<?php0
8P"§
H/workspaces/lab02-laravel/tests/Feature/App/Services/FileStorageTest.phpK
/workspaces/lab02-laravel.tests/Feature/App/Services/FileStorageTest.php<?php0
8P"v
0/workspaces/lab02-laravel/lang/en/validation.php4
/workspaces/lab02-laravel/lang/en/validation.php];(¢0£8P"Ω
U/workspaces/lab02-laravel/tests/Unit/UseCase/Video/CreateVideoUseCaseUnitTest.php.oriY
/workspaces/lab02-laravel/;tests/Unit/UseCase/Video/CreateVideoUseCaseUnitTest.php.ori)(‚0â8"ı
U/workspaces/lab02-laravel/tests/Feature/Core/Usecase/Video/CreateVideoUseCaseTest.phpX
/workspaces/lab02-laravel;tests/Feature/Core/Usecase/Video/CreateVideoUseCaseTest.phpCreateVideoUseCaseTest" Tests\Feature\Core\Usecase\Video(0o8Ph"f
(/workspaces/lab02-laravel/config/app.php,
/workspaces/lab02-laravel/config/app.php];(«0»8P"∞
N/workspaces/lab02-laravel/src/Core/UseCase/Video/Create/CreateVideoUseCase.phpQ
/workspaces/lab02-laravel4src/Core/UseCase/Video/Create/CreateVideoUseCase.php<?php08P"‡
U/workspaces/lab02-laravel/tests/Unit/UseCase/Video/CreateVideoUseCaseUnitTest.php.oriY
/workspaces/lab02-laravel/;tests/Unit/UseCase/Video/CreateVideoUseCaseUnitTest.php.ori$private function createMockInputDTO((Ÿ0·8µ
U/workspaces/lab02-laravel/tests/Feature/Core/Usecase/Video/CreateVideoUseCaseTest.phpŸOOŒ
    /**1
-     * Tests the uploadFilesException method.

     *}
y     * This method forces an exception when storing files to test if the files were successfully written to the database.

     *O
K     * @throws Exception if an error occurs during the file upload process.
     * @return void
     */ ($ab6a277f-9b97-40e5-89c7-7195a9f70bd0
ƒ
¡
%user-zu0MVDCpRm5Y9ZqlxNnTkg4zxyf3EMAKªÆ…±¿Ê÷N" UcFfvYl1sDNwaTgABbGS08apTbZSvGbu*Ê`÷‡
ì
/public function show(ListListVideosUseCase)
{
}/public function show(ListListVideosUseCase)
{
}"show*(ListListVideosUseCase)0'8'@)HPZ    `F/workspaces/lab02-laravel/app/Http/Controllers/Api/VideoController.php
ƒ
¡
%user-OE2Hd8PquNIygYPslWVuTNbc0dxGLVOmªÆ…±¿È◊w" 7yvzlx6bEDnP3SWUeQT6W5GATBTOd8dj*Ê`≠‡
ì
/public function show(ListListVideosUseCase)
{
}/public function show(ListListVideosUseCase)
{
}"show*(ListListVideosUseCase)0'8'@)HPZ    `F/workspaces/lab02-laravel/app/Http/Controllers/Api/VideoController.php
“
©
+status-9f8404e0-b0da-4a44-9d15-2e0fae00c99bªÆ…±›±Ö©" 7yvzlx6bEDnP3SWUeQT6W5GATBTOd8djBH
F"function comment for PHP function
PHP function comment template
$e47aa418-bda0-4b30-868d-c1eb2720dea7
Ò
»
+status-961ef9bb-b7ac-47be-bb4e-59a1cfb1afcfºÆ…±™ÑÏ$" UcFfvYl1sDNwaTgABbGS08apTbZSvGbuBh
f*A description of the entire PHP function.
6A description of the ListListVideosUseCase parameter.
$6343bea0-8f72-4e41-ac8b-51ab9b1cf8b0
‹
≥
(bot-f3437a89-a2c0-48cb-9534-1aa66e12e066æÆ…±ù∏âØ" 7yvzlx6bEDnP3SWUeQT6W5GATBTOd8dj2‘1œ
F/workspaces/lab02-laravel/app/Http/Controllers/Api/VideoController.phpÇ''˜
    /**4
0     * A description of the entire PHP function.

     *1
-     * @param datatype $paramname description@
<     * @throws Some_Exception_Class description of exception$
      * @return Some_Return_Value
     */ ($e47aa418-bda0-4b30-868d-c1eb2720dea7
©
Ä
(bot-b062b838-06db-47c1-a532-50326b6504bc¿Æ…±Ω»˙∑" UcFfvYl1sDNwaTgABbGS08apTbZSvGbu2°?"†
F/workspaces/lab02-laravel/app/Http/Controllers/Api/VideoController.phpI
/workspaces/lab02-laravel,app/Http/Controllers/Api/VideoController.php<?php0
8P"»
F/workspaces/lab02-laravel/app/Http/Controllers/Api/VideoController.phpI
/workspaces/lab02-laravel,app/Http/Controllers/Api/VideoController.phpVideoController"App\Http\Controllers\Api(0)8Ph"–
F/workspaces/lab02-laravel/app/Http/Controllers/Api/VideoController.phpI
/workspaces/lab02-laravel,app/Http/Controllers/Api/VideoController.phpshow"App\Http\Controllers\Api"VideoController('0)8P`h"©
J/workspaces/lab02-laravel/src/Core/UseCase/Video/List/ListVideoUseCase.phpN
/workspaces/lab02-laravel/0src/Core/UseCase/Video/List/ListVideoUseCase.php<?php0	8P"≥
O/workspaces/lab02-laravel/src/Core/UseCase/Video/List/DTO/ListInputVideoDTO.phpS
/workspaces/lab02-laravel/5src/Core/UseCase/Video/List/DTO/ListInputVideoDTO.php<?php08P"µ
P/workspaces/lab02-laravel/src/Core/UseCase/Video/List/DTO/ListOutputVideoDTO.phpT
/workspaces/lab02-laravel/6src/Core/UseCase/Video/List/DTO/ListOutputVideoDTO.php<?php08P"√
W/workspaces/lab02-laravel/tests/Unit/UseCase/Category/ListCategoriesUseCaseUnitTest.php[
/workspaces/lab02-laravel/=tests/Unit/UseCase/Category/ListCategoriesUseCaseUnitTest.php<?php08P"ø
U/workspaces/lab02-laravel/tests/Feature/Core/Usecase/Video/CreateVideoUseCaseTest.phpY
/workspaces/lab02-laravel/;tests/Feature/Core/Usecase/Video/CreateVideoUseCaseTest.php<?php08P"ˆ
J/workspaces/lab02-laravel/src/Core/UseCase/Video/List/ListVideoUseCase.phph
/workspaces/lab02-laravel/J/workspaces/lab02-laravel/src/Core/UseCase/Video/List/ListVideoUseCase.phpexecute"Core\UseCase\Video\List"ListVideoUseCase(0%8P`h"À
[/workspaces/lab02-laravel/tests/Feature/Core/Usecase/Category/ListCategoriesUseCaseTest.php_
/workspaces/lab02-laravel/Atests/Feature/Core/Usecase/Category/ListCategoriesUseCaseTest.php<?php0
8P"Õ
\/workspaces/lab02-laravel/tests/Unit/App/Http/Controllers/Api/CategoryControllerUnitTest.php`
/workspaces/lab02-laravel/Btests/Unit/App/Http/Controllers/Api/CategoryControllerUnitTest.php<?php0
8P"À
[/workspaces/lab02-laravel/tests/Feature/App/Http/Controllers/Api/CategoryControllerTest.php_
/workspaces/lab02-laravel/Atests/Feature/App/Http/Controllers/Api/CategoryControllerTest.php<?php08P"µ
P/workspaces/lab02-laravel/tests/Unit/UseCase/Genre/ListGenresUseCaseUnitTest.phpT
/workspaces/lab02-laravel/6tests/Unit/UseCase/Genre/ListGenresUseCaseUnitTest.php<?php08P"Ω
T/workspaces/lab02-laravel/src/Core/UseCase/Video/Update/DTO/UpdateOutputVideoDTO.phpX
/workspaces/lab02-laravel/:src/Core/UseCase/Video/Update/DTO/UpdateOutputVideoDTO.php<?php08P"ª
S/workspaces/lab02-laravel/src/Core/UseCase/Video/Create/DTO/CreateInputVideoDTO.phpW
/workspaces/lab02-laravel/9src/Core/UseCase/Video/Create/DTO/CreateInputVideoDTO.php<?php08P"ø
U/workspaces/lab02-laravel/tests/Unit/UseCase/Category/ListCategoryUseCaseUnitTest.phpY
/workspaces/lab02-laravel/;tests/Unit/UseCase/Category/ListCategoryUseCaseUnitTest.php<?php08P‹
F/workspaces/lab02-laravel/app/Http/Controllers/Api/VideoController.phpè''Ñ
    /**4
0     * A description of the entire PHP function.

     *>
:     * @param ListListVideosUseCase $paramName description@
<     * @throws Some_Exception_Class description of exception$
      * @return Some_Return_Value
     */ ($6343bea0-8f72-4e41-ac8b-51ab9b1cf8b0 "
{"ops":[]}