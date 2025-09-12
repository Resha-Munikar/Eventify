<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

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

    public function adminEdit($id)
    {
        $user = User::findOrFail($id);
        return view('chirps.edit', compact('user'));
    }

    // Handle update
    public function adminUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'role'  => 'required|string',
        ]);

        $user->update($request->only('name', 'email', 'role'));

        return redirect()->route('chirps.user')
                         ->with('success', 'User updated successfully.');
    }

    public function adminDestroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting yourself (optional)
        if (auth()->id() === $user->id) {
            return redirect()->route('chirps.user')
                            ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('chirps.user')
                        ->with('success', 'User deleted successfully.');
    }

}
