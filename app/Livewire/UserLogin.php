<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;

class UserLogin extends Component
{
    #[Validate('required')]
    public $login;

    #[Validate('required')]
    public $password;

    public function logintest()
    {
        $this->validate();

        $credentials = [
            'login' => $this->login,
            'password' => $this->password,
        ];

        if (Auth::attempt($credentials)) {
            session()->regenerate();

            return redirect()->to('/');
        }

        return back()->withErrors([
            'loginr' => 'Неверный логин или пароль',
        ]);
    }

    public function render()
    {
        return view('livewire.user-login');
    }
}
