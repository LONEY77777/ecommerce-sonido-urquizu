<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Paginated user list, searchable by name or email.
     */
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();

        return view('admin.users.index', [
            'users' => User::search($search ?: null)
                ->withCount('orders')
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'search' => $search,
        ]);
    }

    /**
     * Show a user alongside their order history.
     */
    public function show(User $user): View
    {
        return view('admin.users.show', [
            'user' => $user,
            'orders' => $user->orders()->withCount('items')->latest()->paginate(10),
        ]);
    }

    /**
     * Delete a customer account. Administrators cannot be removed here.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->isAdmin()) {
            return back()->with('error', 'Administrator accounts cannot be deleted.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}
