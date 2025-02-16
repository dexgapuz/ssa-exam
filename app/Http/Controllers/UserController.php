<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $users = $this->userService->list();

        return Inertia::render('Dashboard', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('User/UserForm', [
            'isEdit' => false,
            'user' => []
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $this->userService->store($data);

        return redirect('/');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $user): Response
    {
        $data = $this->userService->find($user);

        return Inertia::render('User/Profile', ['user' => $data]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $user)
    {
        $data = $this->userService->find($user);

        return Inertia::render('User/UserForm', [
            'isEdit' => true,
            'user' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, int $user): RedirectResponse
    {
        $data = $request->validated();

        $this->userService->update($user, $data);

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $user): RedirectResponse
    {
        $this->userService->destroy($user);

        return redirect('/');
    }

    public function trashed(): Response
    {
        $users = $this->userService->listTrashed();

        return Inertia::render('User/Trashed', ['users' => $users]);
    }

    public function restore(int $user): RedirectResponse
    {
        $this->userService->restore($user);

        return redirect('/user/trashed');
    }

    public function delete(int $user): RedirectResponse
    {
        $this->userService->delete($user);

        return redirect('/user/trashed');
    }
}
