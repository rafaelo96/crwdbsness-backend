<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // Aplica el middleware de autenticación, solo usuarios autenticados pueden acceder a este endpoint
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function show($id)
    {
        // Verifica si el token es válido y si el usuario existe
        $user = User::find($id);

        if ($user) {
            return response()->json($user);
        } else {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
    }
}
