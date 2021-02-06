<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @param \Requests\AuthRequest $request
     * 
     * @return \Flugg\Responder\Facades\Responder responder()
     */
    public function login(LoginRequest $request)
    {
        //  Authenticate email address.
        if ($token = auth()->attempt($request->only('email', 'password'))) {
            return $this->respondWithToken($token);
        }

        //  Authenticate username.
        if ($token = auth()->attempt([
            'username' => $request->email, 
            'password' => $request->password
        ])){
            return $this->respondWithToken($token);
        }

        return responder()->error('These credentials do not match our records.')
                          ->respond(401);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Flugg\Responder\Facades\Responder responder()
     */
    public function me()
    {
        $authUser = auth()->user()->load(['information', 'calendar']);

        return responder()->success([
            'user' => $authUser,
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Flugg\Responder\Facades\Responder responder()
     */
    public function logout()
    {
        auth()->logout();

        return responder()->success([
            'message' => 'Successfully logout'
        ]);
    }

    /**
     * Refresh a token.
     *
     * @return respondWithToken
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     * @return \Flugg\Responder\Facades\Responder responder()
     */
    protected function respondWithToken($token)
    {
        return responder()->success([
            'token'         => $token,
            'token_type'    => 'bearer',
            'expires_in'    => auth()->factory()->getTTL() * 60
        ]);
    }
}
