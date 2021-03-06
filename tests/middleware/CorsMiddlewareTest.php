<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Middleware\Cors;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class CorsMiddlewareTest extends TestCase
{
    public function test_correct_headers_are_set()
    {
        $request = Request::create('/', 'POST');

        $middleware = new Cors;

        $response = new Response;

        $response = $middleware->handle($request, function() use ($response) {
            return $response;
        });

        $this->assertEquals($response->headers->get('Access-Control-Allow-Origin'), '*');
        $this->assertEquals(
            $response->headers->get('Access-Control-Allow-Methods'),
            'HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS'
        );
        $this->assertEquals($response->headers->get('Access-Control-Allow-Headers'), 'Content-Type, Authorization');

    }

    public function test_preflight_request_is_handled()
    {
        $request = Request::create('/', 'OPTIONS');

        $middleware = new Cors;

        $response = $middleware->handle($request, function() {

        });

        $this->assertEquals($response->headers->get('Access-Control-Allow-Origin'), '*');
        $this->assertEquals(
            $response->headers->get('Access-Control-Allow-Methods'),
            'HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS'
        );
        $this->assertEquals($response->headers->get('Access-Control-Allow-Headers'), 'Content-Type, Authorization');

        $this->assertEquals($response->getStatusCode(), 200);
    }
}
