<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User as User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Exception;
class AuthController extends Controller
{
    /**
     * Registro de usuario
     */
    public function signUp(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string'
            ]);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            return response()->json([
                'message' => 'Successfully created user!'
            ], 201);
            
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Unexpected error occured. Please try again later.',
                'exception' => get_class($ex),
                'message' => $ex->getMessage()
            ], 500);
        }
    }
  
    /**
     * Inicio de sesión y creación de token
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
                'remember_me' => 'boolean'
            ]);

            $credentials = request(['email', 'password']);

            if (!$token = Auth::attempt($credentials)) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }

            $user = $request->user();
            $token = $user->createToken('apiToken');
            $token->expires_at = Carbon::now()->addMinutes(30);
            $response = ['token' => $token->accessToken];
            return response($response, 200);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Unexpected error occured. Please try again later.',
                'exception' => get_class($ex),
                'message' => $ex->getMessage()
            ], 500);
        }
    }


  
    /**
     * Cierre de sesión (anular el token)
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
  
    /**
     * Obtener el objeto User como json
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
    
    
}