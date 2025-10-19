<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\User\StoreUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return self::paginated(User::paginate(10), null, 'Users retrieved successfully', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());
        return self::success($user, 'User created successfully', 201);   
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return self::success($user, 'User retrieved successfully', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $user->update($request->validated());
        return self::success($user, 'User updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return self::success(null, 'User deleted successfully', 200);
    }
}
