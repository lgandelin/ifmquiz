<?php

namespace Webaccess\IFMQuiz\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function index(Request $request)
    {
        return view('ifmquiz::back.login', [
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
        ]);
    }

    public function login()
    {
        if (Auth::guard('administrators')->attempt([
            'email' => $this->request->input('email'),
            'password' => $this->request->input('password'),
        ])) {
            return redirect()->route('quiz_list');
        }

        return redirect()->route('login')->with([
            'error' => 'Email non trouvé ou mot de passe invalide.',
        ]);
    }

    public function logout()
    {
        Auth::guard('administrators')->logout();

        return redirect()->route('login');
    }
}