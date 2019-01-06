<?php

use App\Link;
use Carbon\Carbon;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class LinkCreationTest extends TestCase
{
    public function test_fails_if_no_url_given()
    {
        $response = $this->json('POST', '/')
            ->notSeeInDatabase('links', [
                'code' => '1'
            ])
            ->seeJson(['url' => ['Please enter a URL to shorten.']])
            ->assertResponseStatus(422);
    }
}
