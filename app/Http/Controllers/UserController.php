<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Enums\UserType;
use App\Http\Requests\UserRequest;
use App\Models\Inventory;
use App\Models\User;
use App\Services\UserService;
use Exception;
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
            ->orderBy('id', 'DESC')
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
        try {
            $validatedFields = $userRequest->validated();
            User::create($validatedFields);
        } catch (Exception $e) {
            return redirect()->route('users.index')->with('error', 'Something went wrong');
        }

        return redirect()->route('users.index')->with('success', 'User added successfully');
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
        try {
            $validatedFields = $userRequest->validated();

            $user = User::findOrFail($id);
            $user->update($validatedFields);
        } catch (Exception $e) {
            return redirect()->route('users.index')->with('error', 'Something went wrong');
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            $inventory = Inventory::where('user_id', $id);
            if ($inventory->count()) {
                return back()->with('error', 'Cannot delete user. Inventory is associated with this user.');
            }

            $user->delete();
        } catch (Exception $e) {
            return back()->with('error', 'Something went wrong');
        }

        return back()->with('success', 'User deleted successfully');
    }
}
