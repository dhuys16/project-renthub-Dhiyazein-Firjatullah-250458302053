<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\User;

class Login extends Component
{
    /** @var string */
    public $loginId = ''; // Digunakan untuk menampung input username atau email

    /** @var string */
    public $password = '';

    /** @var bool */
    public $remember = false;

    protected $rules = [
        // Validasi umum untuk memastikan input tidak kosong
        'loginId' => ['required', 'string'], 
        'password' => ['required'],
    ];

    public function authenticate()
    {
        $this->validate();

        $fieldType = filter_var($this->loginId, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $fieldType => $this->loginId,
            'password' => $this->password,
        ];
        
        // 1. Coba Otentikasi
        if (!Auth::attempt($credentials, $this->remember)) {
            $this->addError('loginId', trans('auth.failed')); 
            return;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user(); // <<< BARIS INI WAJIB DITAMBAHKAN!

        if ($user->isAdmin()) {
            return redirect()->intended(route('home'));
        } 
        
        if ($user->isVendor()) {
            // Asumsi: route vendor.dashboard sudah didefinisikan
            return redirect()->intended(route('home'));
        }
        
        // Default: Customer
        return redirect()->intended(route('home')); 
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.auth', ['title' => 'Login']);
    }
}