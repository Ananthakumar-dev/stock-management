<?php

namespace App\Services;

use App\Models\User;
use App\Enums\Status;
use Illuminate\Support\Facades\DB;
use App\Enums\UserType;

class UserService
{
    /**
     * get all users
     */
    public function getUsers()
    {
        $users = DB::table('users');

        return $users;
    }
}
