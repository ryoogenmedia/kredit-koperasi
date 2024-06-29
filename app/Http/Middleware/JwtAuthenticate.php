<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if(!$token){
            return response()->json([
                'status' => 'error',
                'message' => 'token not provided.'
            ], 401);
        }

        try{
            $decoded = JWT::decode($token, new Key(config('jwt.secret'), 'HS256'));
            $user = User::query()->with(['nasabah'])->where('id', $decoded->sub)->first();
            $request->attributes->set('auth', $user);

        }catch(Exception $e){
            return response()->json([
                'status' => 'error',
                'message' => 'Token invalid.'
            ], 401);
        }

        return $next($request);
    }
}
