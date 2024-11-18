<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        try {
            // Autenticar al usuario con las credenciales
            $request->authenticate();

            // Obtener el usuario autenticado
            $user = auth()->user();

            // Crear un nuevo token para el usuario autenticado
            $token = $user->createToken('crwdbsness')->plainTextToken;

            // Devolver la respuesta con el token y el usuario
            return response()->json([
                'status' => 200,
                'user' => $user,
                'token' => $token,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        // Obtener el token enviado desde el frontend
        $token = $request->bearerToken();

        // Si hay un token, eliminarlo de la base de datos
        if ($token) {
            // Aquí puedes eliminar el token de la base de datos manualmente
            DB::table('personal_access_tokens')
                ->where('token', hash('sha256', $token))  // Asegúrate de hashear el token correctamente
                ->delete();
        }

        // También puedes utilizar el logout para la sesión actual de Laravel si usas sesiones tradicionales
        Auth::logout();

        // Devolver una respuesta JSON indicando que el logout fue exitoso
        return response()->json(['message' => 'Logout exitoso'], 200);
    }
}