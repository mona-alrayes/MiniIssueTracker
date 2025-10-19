<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegiterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\User\AuthService;

/**
 * Authentication Controller
 * 
 * Handles HTTP requests for user authentication operations.
 * Delegates business logic to AuthService.
 */
class AuthController extends Controller
{
    protected AuthService $authService;

    /**
     * Constructor
     * 
     * @param AuthService $authService Authentication service instance
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register a new user
     * 
     * @param RegiterRequest $request Validated registration request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegiterRequest $request)
    {
        $response = $this->authService->register($request->validated());
        return self::success($response, 'User registered successfully', 201);
    }

    /**
     * Login user
     * 
     * @param LoginRequest $request Validated login request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        return self::success($this->authService->login($request->validated()), 'User logged in successfully', 200);
    }

    /**
     * Logout user
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        return self::success($this->authService->logout(), 'User logged out successfully', 200);
    }

}