<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class MathTest extends TestCase
{
    protected $mappings = [
        1 => 1,
        100 => '1C',
        1000000 => '4c92',
        999999999 => '15FTGf'
    ];

    public function test_correctly_encodes_values()
    {
        $math = new App\Helpers\Math;

        foreach ($this->mappings as $value => $encodes) {
            $this->assertEquals($encodes, $math->toBase($value));
        }
    }
}
