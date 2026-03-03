<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('organization')
            ->orderBy('name')
            ->paginate(15);

        return view('users.index', compact('users'));
    }
}
