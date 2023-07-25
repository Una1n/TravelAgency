<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(StoreUserRequest $request): JsonResource
    {
        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
        ]);

        if ($request->has('role')) {
            $role = Role::query()->whereName($request->validated('role'))->first();
            $user->roles()->attach($role);
        }

        return UserResource::make($user)->additional(
            ['message' => 'User created successfully.']
        );
    }
}
