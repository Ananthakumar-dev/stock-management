<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Enums\UserType;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * index page
     */
    public function index(
        UserService $userService,
        Request $request
    ) {
        $search = $request->query('search');

        $allUsers = $userService
            ->getUsers()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('designation', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
            })
            ->where('type', UserType::User)
            ->paginate(PAGINATION)
            ->withQueryString();

        return Inertia::render('User/Index/Index', [
            'users' => $allUsers,
            'initialSearch' => $search
        ]);
    }

    /**
     * user create form
     */
    public function create()
    {
        $statuses = Status::cases();

        return Inertia::render('User/Add/Index', [
            'statuses' => $statuses
        ]);
    }

    public function store(
        UserRequest $userRequest
    ) {
        $validatedFields = $userRequest->validated();
        User::create($validatedFields);

        return redirect()->route('users.index');
    }

    public function show(
        int $id
    ) {
        $statuses = Status::cases();
        $user = User::findOrFail($id);

        return Inertia::render('User/Edit/Index', [
            'statuses' => $statuses,
            'user' => $user
        ]);
    }

    public function update(
        UserRequest $userRequest,
        $id
    ) {
        $validatedFields = $userRequest->validated();

        $user = User::findOrFail($id);
        $user->update($validatedFields);

        return redirect()->route('users.index');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index');
    }
}
