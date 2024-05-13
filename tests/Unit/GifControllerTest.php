<?php

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use App\Http\Controllers\GifController;
use App\Models\GifsModel;
use PHPUnit\Framework\TestCase;
use Illuminate\Validation\Validator;
use Illuminate\Validation\ValidationException;


const KEY_GHIPY = 'RcAftMSyiRM5FPVtOt2ls4528p1EyYVu';
const URL_GHIPY = 'http://api.giphy.com/v1/gifs/search';
class GifControllerTest extends TestCase
{
    public function testSearchWithValidParameters()
    {
        // Arrange
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('validate')->with([
            'QUERY' => 'required|string',
            'LIMIT' => 'numeric',
            'OFFSET' => 'numeric',
        ])->once();

        $request->shouldReceive('input')->with('QUERY')->andReturn('test');
        $request->shouldReceive('input')->with('LIMIT', 25)->andReturn(25);
        $request->shouldReceive('input')->with('OFFSET', 0)->andReturn(0);

        $client = Mockery::mock(Client::class);
        $client->shouldReceive('request')->with('GET', URL_GHIPY, [
            'query' => [
                'api_key' => 'RcAftMSyiRM5FPVtOt2ls4528p1EyYVu',
                'q' => 'test',
                'limit' => 25,
                'offset' => 0,
            ],
        ])->andReturn(new Response(200, [], '{"data":[]}'));

        $gifModel = Mockery::mock(GifsModel::class);
        $gifModel->shouldReceive('addActivity')->with([
            'user' => 1,
            'ip' => '127.0.0.1',
            'service' => 'App\Http\Controllers\GifController@search',
            'response_body' => '{"data":[]}',
            'http_code' => 200,
        ])->andReturn(true);

        $controller = new GifController($client, $gifModel);

        // Act
        $response = $controller->search($request);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('{"data":[]}', $response->getContent());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testSearchWithInvalidParameters()
    {
        // Arrange
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('validate')->with([
            'QUERY' => 'required|string',
            'LIMIT' => 'numeric',
            'OFFSET' => 'numeric',
        ])->andThrow(new \Illuminate\Validation\ValidationException(Mockery::mock('Illuminate\Validation\Validator')));

        $controller = new GifController(Mockery::mock(Client::class), Mockery::mock(GifsModel::class));

        // Act
        $response = $controller->search($request);

        // Assert
        $this->assertEquals(500, $response->getStatusCode());
        $this->assertArrayHasKey('error', json_decode($response->getContent(), true));
        $this->assertArrayHasKey('exception', json_decode($response->getContent(), true));
        $this->assertArrayHasKey('message', json_decode($response->getContent(), true));
    }
}