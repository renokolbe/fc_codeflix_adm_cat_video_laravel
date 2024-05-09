<?php

namespace Tests\Feature\App\Services;

use App\Services\Storage\FileStorage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileStorageTest extends TestCase
{
    public function testStore()
    {
        $fakeFile = UploadedFile::fake()->create('video.mp4', 1, 'video/mp4');

        //dump($fakeFile);

        $file = [
            'tmp_name' => $fakeFile->getPathname(),
            'name' => $fakeFile->getFileName(),
            'type' => $fakeFile->getMimeType(),
            'error' => $fakeFile->getError(),
            'size' => $fakeFile->getSize()
        ];

        //dump($file);

        $filePath = (new FileStorage())->store(path: 'videos', file: $file);

        //dump(storage_path(env('GOOGLE_CLOUD_KEY_FILE', null)));
        //dump($filePath);

        //$this->assertTrue(true);

        Storage::assertExists($filePath);

        //Excluir arquivo de teste
        Storage::delete($filePath);

    }
    public function testDelete()
    {
        $fakeFile = UploadedFile::fake()->create('video.mp4', 1, 'video/mp4');

        $path = $fakeFile->store('videos');

        (new FileStorage())->delete(path: $path);

        Storage::assertMissing($path);

    }
}
