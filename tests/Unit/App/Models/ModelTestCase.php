<?php

namespace Tests\Unit\App\Models;

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;

abstract class ModelTestCase extends TestCase
{
    abstract protected function model(): Model;
    abstract protected function traits(): array;
    abstract protected function fillables(): array;
    abstract protected function casts(): array;
    
    public function testIfUseTraits()
    {
        $traitsNeeded = $this->traits();

        $traitsUsed = array_keys(class_uses($this->model()));

        $this->assertEquals($traitsNeeded, $traitsUsed);
    }

    public function testFillables(){

        $fillablesNeeded = $this->fillables();

        $fillablesUsed = $this->model()->getFillable();

        $this->assertEquals($fillablesNeeded, $fillablesUsed);
    }

    public function testIncrementingIsFalse(){
        $model = $this->model();
        $this->assertFalse($model->incrementing);
    }

    public function testHasCasts(){

        $castNeeded = $this->casts();

        $castUsed = $this->model()->getCasts();

        $this->assertEquals($castNeeded, $castUsed);
    }

}