<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Database\QueryException;

class RegisteredUserController extends Controller
{
    public function store(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        try {
            // Crear un nuevo usuario
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            // Disparar el evento de registro
            event(new Registered($user));
    
            // Iniciar sesión automáticamente al usuario recién registrado
            Auth::login($user);
            // Crear el token
            $token = $user->createToken('crwdbsness')->plainTextToken;
    
            // Responder con los datos del usuario y el token
            return response()->json([
                'status' => 201,
                'message' => 'Usuario registrado con éxito.',
                'user' => $user,
                'token' => $token,
            ], 201);
            
        } catch (QueryException $e) {
            // Manejar el error de correo duplicado
            if ($e->getCode() == 23000) {
                return response()->json([
                    'status' => 400,
                    'error' => 'Este correo electrónico ya está registrado.',
                ], 400);
            }
    
            // Si ocurre otro tipo de error, devolver un error genérico
            return response()->json([
                'status' => 500,
                'error' => 'Hubo un problema al procesar tu solicitud.',
            ], 500);
        }
    }
}
