<?php

namespace App\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;


class Register extends Component
{
    use WithFileUploads;
    /** @var string */
    public $username = '';

    public $ktp = '';

    public $name = '';

    /** @var string */
    public $email = '';

    /** @var string */
    public $password = '';

    /** @var string */
    public $address = ''; 
    
    public $link_gmaps = ''; 
    
    /** @var string */
    public $phone_number = ''; 
    // ...

    /** @var string */
    public $passwordConfirmation = '';

    #[Rule('accepted', message: 'Anda harus menyetujui syarat dan ketentuan.')]
    public bool $terms = false;

    public function register()
    {
        $this->validate([
            'username' => ['required', 'unique:users', 'string', 'max:50'],
            'email' => ['required', 'email', 'unique:users'],
            'address' => ['required', 'string'], 
            'link_gmaps' => ['required', 'string'],
            'phone_number' => ['required', 'string', 'max:15'], 
            'password' => ['required', 'min:8', 'same:passwordConfirmation'],
            'ktp' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'name' => ['required', 'string', 'max:255'],
            'terms' => ['accepted'],
        ]);

        $ktpPath = null;
    
        // [PERBAIKAN]: Cek apakah $this->ktp adalah objek UploadedFile sebelum memanggil store()
        if ($this->ktp) { 
            // Menyimpan file KTP ke storage dan mendapatkan path-nya
            $ktpPath = $this->ktp->store('ktp_uploads', 'public');
        }
        // === END: LOGIKA UPLOAD FILE KTP ===

        $user = User::create([
            'email' => $this->email,
            'ktp' => $ktpPath,
            'name' => $this->name,
            'username' => $this->username,
            'address' => $this->address, // <<< SIMPAN DATA BARU
            'link_gmaps' => $this->link_gmaps, // <<< SIMPAN DATA BARU
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
