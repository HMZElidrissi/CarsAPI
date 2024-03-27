<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(Gate::denies('manage-users'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::users()->get();

        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        abort_if(Gate::denies('manage-users'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $attributes = $request->validated();
        $attributes['password'] = bcrypt($attributes['password']);

        $user = User::create($attributes);

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        abort_if(Gate::denies('manage-users'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        abort_if(Gate::denies('manage-users'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $attributes = $request->validated();
        $user->update($attributes);

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        abort_if(Gate::denies('manage-users'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user->delete();

        return response()->json(null, 204);
    }
}
