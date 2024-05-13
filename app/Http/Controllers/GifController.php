<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\GifsModel;
use Illuminate\Support\Facades\Route; 
use Exception;
const KEY_GHIPY = 'RcAftMSyiRM5FPVtOt2ls4528p1EyYVu';
const URL_GHIPY = 'http://api.giphy.com/v1/gifs/search';

class GifController extends Controller
{
    /**
     * Search for GIFs using the Giphy API.
     * 
     * This method validates the incoming request to ensure that the required parameters are present
     * and then performs a GET request to the Giphy API with the search term, limit, and offset parameters.
     * The response from the Giphy API is then returned to the client as a JSON response.
     *
     * @param  \Illuminate\Http\Request  $request The request object containing the search parameters.
     * @return \Illuminate\Http\Response         The response object containing the GIFs from Giphy API.
     */
    public function search(Request $request)
    {
        try{
            // Validate the incoming request parameters
            $request->validate([
                'QUERY' => 'required|string', // The search term for the GIFs
                'LIMIT' => 'numeric',         // The maximum number of GIFs to return
                'OFFSET' => 'numeric',        // The offset for pagination
            ]);

            // Extract the search term, limit, and offset from the request
            $searchTerm = $request->input('QUERY');
            $limit = $request->input('LIMIT', 25); // Default limit is 25
            $offset = $request->input('OFFSET', 0); // Default offset is 0

            // Create a new Guzzle HTTP client instance
            $client = new Client();

            // Perform a GET request to the Giphy API with the specified parameters
            $response = $client->request('GET', URL_GHIPY, [
                'query' => [
                    'api_key' => KEY_GHIPY, // The API key for Giphy API
                    'q' => $searchTerm,      // The search term
                    'limit' => $limit,       // The limit on the number of GIFs to return
                    'offset' => $offset      // The offset for pagination
                ]
            ]);
            $gifModel = new GifsModel();
            $body = $response->getBody()->getContents();
            $register = $gifModel->addActivity(
                [
                'user' => $request->user()->id,
                'ip' => $request->ip(),
                'service' => Route::getCurrentRoute()->getActionName(),
                'response_body' => $body,
                'http_code' => $response->getStatusCode(),
            ]);
            // Return the response from Giphy API as a JSON response to the client
            return $register ? response($body, $response->getStatusCode())
                ->header('Content-Type', 'application/json') :
                response($body, 500)
                ->header('Content-Type', 'application/json');
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Unexpected error occured. Please try again later.',
                'exception' => get_class($ex),
                'message' => $ex->getMessage()
            ], 500);
        }
    }
    /**
     * Perform a search for a GIF by its ID using the Giphy API.
     *
     * @param Request $request The request object containing the GIF ID.
     * @throws N/A
     * @return \Illuminate\Http\Response The response object containing the GIF data from Giphy API.
     */
    public function searchById(Request $request){
        try {
            $request->validate([
                'ID' => 'required|string',
            ]);
            $id = $request->input('ID');
            $apiKey = KEY_GHIPY;
            $response = Http::get("https://api.giphy.com/v1/gifs/{$id}?api_key={$apiKey}");
            $gifModel = new GifsModel();            
            $body = $response->getBody()->getContents();
            $register = $gifModel->addActivity(
                [
                'user' => $request->user()->id,
                'ip' => $request->ip(),
                'service' => Route::getCurrentRoute()->getActionName(),
                'response_body' => $body,
                'http_code' => $response->getStatusCode(),
                ]);
                return $register ? response($body, $response->getStatusCode())
                ->header('Content-Type', 'application/json') :
                response($body, 500)
                ->header('Content-Type', 'application/json');
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Unexpected error occured. Please try again later.',
                'exception' => get_class($ex),
                'message' => $ex->getMessage()
            ], 500);
        }
    }
    
    public function registerFavorites(Request $request){        
        try {
            $request->validate([
                'GIF_ID' => 'required|numeric',
                'ALIAS' => 'required|string',
                'USER_ID' => 'required|numeric',
            ]);
            $gifId = $request->input('GIF_ID');
            $alias = $request->input('ALIAS');
            $userId = $request->input('USER_ID');
            $gifModel = new GifsModel();
            $response = $gifModel->addFavorites([
            'id_gif' => $gifId,
            'alias' => $alias,
            'id_user' => $userId,
            ]);
            $responseBody = $response ? ['message' => 'GIF favorito guardado correctamente', 
            'status'=>'200'] : ['message' => 'Error al registrar favorito', 
            'status'=>'500'];
            $gifModel = new GifsModel();
            $gifModel->addActivity(
                [
                'user' => $request->user()->id,
                'ip' => $request->ip(),
                'service' => Route::getCurrentRoute()->getActionName(),
                'response_body' => json_encode($responseBody),
                'http_code' => $response ? 200:500,
                ]);
            return response()->json($responseBody);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Unexpected error occured. Please try again later.',
                'exception' => get_class($ex),
                'message' => $ex->getMessage()
            ], 500);
        }
    }
    
}
