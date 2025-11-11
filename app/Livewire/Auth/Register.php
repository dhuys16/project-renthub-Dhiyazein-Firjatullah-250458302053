<?php

namespace App\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Livewire\Component;

class Register extends Component
{
    /** @var string */
    public $username = '';

    /** @var string */
    public $email = '';

    /** @var string */
    public $password = '';

    /** @var string */
    public $address = ''; 
    
    /** @var string */
    public $phone_number = ''; 
    // ...

    /** @var string */
    public $passwordConfirmation = '';

    public function register()
    {
        $this->validate([
            'username' => ['required', 'unique:users', 'string', 'max:50'],
            'email' => ['required', 'email', 'unique:users'],
            'address' => ['required', 'string'], 
            'phone_number' => ['required', 'string', 'max:15'], 
            'password' => ['required', 'min:8', 'same:passwordConfirmation'],
        ]);

        $user = User::create([
            'email' => $this->email,
            'username' => $this->username,
            'address' => $this->address, // <<< SIMPAN DATA BARU
            'phone_number' => $this->phone_number, // <<< SIMPAN DATA BARU
            'role' => 'customer',
            'password' => Hash::make($this->password),
        ]);

    event(new Registered($user));

    Auth::login($user, true);

    return redirect()->intended(route('home'));
    }

    public function render()
    {
        /**
     * @return \Illuminate\Contracts\View\View
     *
     */
        return view('livewire.auth.register')->layout('layouts.auth', ['title' => 'Daftar Akun Baru']);
    }
}
