<?php

namespace App\Services\User;

use App\Exceptions\ApiException;
use App\Models\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Authentication Service
 * 
 * Handles user authentication operations including registration, login, and logout
 * using JWT tokens for API authentication.
 */
class AuthService
{
    /**
     * Register a new user and generate JWT token
     * 
     * @param array $data User registration data (name, email, password)
     * @return array Contains token and user object
     * @throws ApiException If token creation fails
     */
    public function register(array $data)
    {
        $user = User::create($data);

        try {
            $token = JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            throw new ApiException('Could not create token', 500);
        }

        return [
            'token' => $token,
            'user' => $user,
        ];
    }

    /**
     * Authenticate user and generate JWT token
     * 
     * @param array $credentials User credentials (email, password)
     * @return array Contains token and expiration time
     * @throws ApiException If credentials are invalid or token creation fails
     */
    public function login(array $credentials)
    {
        $credentials = [
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ];

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                throw new ApiException('Invalid credentials', 401);
            }
        } catch (JWTException $e) {
            throw new ApiException('Could not create token', 500);
        }

        return [
            'token' => $token,
            'expires_in' => config('jwt.ttl') * 60,
        ];
    }

    /**
     * Logout user by invalidating JWT token
     * 
     * @return array Success message
     * @throws ApiException If token invalidation fails
     */
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (JWTException $e) {
            throw new ApiException('Failed to logout, please try again', 500);
        }

        return ['message' => 'Successfully logged out'];
    }
}