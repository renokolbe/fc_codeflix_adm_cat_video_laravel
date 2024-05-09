<?php

namespace Tests\Feature\Api\Auth;

use Illuminate\Http\Response;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    public function test_authentication_api_category()
    {
        $this->getJson('/api/categories')->assertStatus(401);
        $this->getJson('/api/categories/fake-id')->assertStatus(401);
        $this->postJson('/api/categories')->assertStatus(401);
        $this->putJson('/api/categories/fake-id')->assertStatus(401);
        $this->deleteJson('/api/categories/fake-id')->assertStatus(401);
    }

    public function test_authentication_api_genre()
    {
        $this->getJson('/api/genres')->assertStatus(401);
        $this->getJson('/api/genres/fake-id')->assertStatus(401);
        $this->postJson('/api/genres')->assertStatus(401);
        $this->putJson('/api/genres/fake-id')->assertStatus(401);
        $this->deleteJson('/api/genres/fake-id')->assertStatus(401);
    }

    public function test_authentication_api_cast_members()
    {
        $this->getJson('/api/cast_members')->assertStatus(401);
        $this->getJson('/api/cast_members/fake-id')->assertStatus(401);
        $this->postJson('/api/cast_members')->assertStatus(401);
        $this->putJson('/api/cast_members/fake-id')->assertStatus(401);
        $this->deleteJson('/api/cast_members/fake-id')->assertStatus(401);
    }

    public function test_authentication_api_video()
    {
        $this->getJson('/api/videos')->assertStatus(401);
        $this->getJson('/api/videos/fake-id')->assertStatus(401);
        $this->postJson('/api/videos')->assertStatus(401);
        $this->putJson('/api/videos/fake-id')->assertStatus(401);
        $this->deleteJson('/api/videos/fake-id')->assertStatus(401);
    }

}