<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserManagementController extends Controller
{
    /**
     * Menampilkan daftar SEMUA pengguna (Admin, Vendor, Customer).
     * (Route: GET /admin/users)
     */
    public function index()
    {
        $users = User::latest()->paginate(20);

        return view('admin.users', compact('users'));
    }

    /**
     * Menampilkan formulir untuk membuat pengguna baru.
     * (Route: GET /admin/users/create)
     */
    public function create()
    {
        $roles = ['admin', 'vendor', 'customer']; 
        
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Menyimpan pengguna baru ke database.
     * (Route: POST /admin/users)
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => ['required', Rule::in(['admin', 'vendor', 'customer'])],
        ]);

        User::create([
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail pengguna tertentu.
     * (Route: GET /admin/users/{user})
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Menampilkan formulir untuk mengedit pengguna (termasuk mengganti role).
     * (Route: GET /admin/users/{user}/edit)
     */
    public function edit(User $user)
    {
        $roles = ['admin', 'vendor', 'customer'];
        
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Memperbarui pengguna yang sudah ada di database.
     * (Route: PUT/PATCH /admin/users/{user})
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => ['required', Rule::in(['admin', 'vendor', 'customer'])],
            'password' => 'nullable|min:8',
        ]);

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {

            unset($validatedData['password']);
        }

        $user->update($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Menghapus pengguna dari database.
     * (Route: DELETE /admin/users/{user})
     */
    public function destroy(User $user)
    {
       
        $user->delete();

    return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
}
}