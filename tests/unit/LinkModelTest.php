<?php

use App\Link;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class LinkModelTest extends TestCase
{
    protected $mappings = [
        1 => 1,
        100 => '1C',
        1000000 => '4c92',
        999999999 => '15FTGf'
    ];

    public function test_correct_code_is_generated()
    {
        $link = new Link;

        foreach ($this->mappings as $id => $expectedCode) {
            $link->id = $id;
            $this->assertEquals($link->getCode(), $expectedCode);
        }
    }

    public function test_exception_is_thrown_with_no_id()
    {
        $this->expectException(\App\Exceptions\CodeGenerationException::class);
        $link = new Link;
        $link->getCode();
    }

    public function test_can_get_model_by_code()
    {
        $link = factory(Link::class)->create([
            'code' => 'abc'
        ]);

        $model = $link->byCode($link->code)->first();

        $this->assertInstanceOf(Link::class, $model);
        $this->assertEquals($model->original_url, $link->original_url);
    }
}
