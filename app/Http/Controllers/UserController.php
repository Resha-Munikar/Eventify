<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function adminView()
    {
        // Get all users
        $users = User::all();

        // Pass them to the view
        return view('chirps.user', compact('users'));
    }
}
