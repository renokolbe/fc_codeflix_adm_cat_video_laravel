<?php

namespace Tests\Traits;
/**
 * Trait para Desabilitar, em testes os Middleares de Autenticação e Autorização
 */

trait WithoutMiddlewareTrait
{
    protected function setUp(): void
    {
        parent::setUp();

        // Desabilitar o middleware de Autenticacao
        $this->withoutMiddleware([
            \App\Http\Middleware\Authenticate::class,
            \Illuminate\Auth\Middleware\Authorize::class,
        ]);
    }

}