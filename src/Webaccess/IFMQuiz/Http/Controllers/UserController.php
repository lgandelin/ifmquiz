<?php

namespace Webaccess\IFMQuiz\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use Webaccess\IFMQuiz\Models\Administrator;
use Webaccess\WineSupervisorLaravel\Repositories\CellarRepository;
use Webaccess\WineSupervisorLaravel\Repositories\UserRepository;

class UserController
{
    public function index(Request $request)
    {
        return view('ifmquiz::back.user.index', [
            'users' => Administrator::all(),

            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function create(Request $request)
    {
        return view('ifmquiz::back.user.create', [
            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function create_handler(Request $request)
    {
        if ($request->get('password') != $request->get('password_confirm')) {
            $request->session()->flash('error', 'Les 2 mots de passes entrés ne correspondent pas');
            return redirect()->back()->withInput();
        }

        $user = new Administrator();
        $user->id = Uuid::uuid4()->toString();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        if ($request->password != '') $user->password = Hash::make($request->password);

        if (!$user->save()) {
            $request->session()->flash('error', 'Une erreur est survenue lors de la création de l\'utilisateur. Veuillez retentez l\'opération.');

            return redirect()->back()->withInput();
        }

        $request->session()->flash('confirmation', 'Création de l\'utilisateur effectuée avec succès');

        return redirect()->route('user_list');
    }

    public function update(Request $request, $userID)
    {
        return view('ifmquiz::back.user.update', [
            'user' => Administrator::find($userID),

            'error' => ($request->session()->has('error')) ? $request->session()->get('error') : null,
            'confirmation' => ($request->session()->has('confirmation')) ? $request->session()->get('confirmation') : null,
        ]);
    }

    public function update_handler(Request $request)
    {
        if ($request->get('password') != $request->get('password_confirm')) {
            $request->session()->flash('error', 'Les 2 mots de passes entrés ne correspondent pas');
            return redirect()->back()->withInput();
        }

        if (!$user = Administrator::find($request->uuid)) {
            $request->session()->flash('error', 'Utilisateur non trouvé. Veuillez vérifier que l\'utilisateur existe bien.');

            return redirect()->back()->withInput();
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        if ($request->password != '') $user->password = Hash::make($request->password);

        if (!$user->save()) {
            $request->session()->flash('error', 'Une erreur est survenue lors de la mise à jour de l\'utilisateur. Veuillez retentez l\'opération.');

            return redirect()->back()->withInput();
        }

        $request->session()->flash('confirmation', 'Mise à jour de l\'utilisateur effectuée avec succès');

        return redirect()->route('user_list');
    }

    public function delete(Request $request, $userID)
    {
        $user = Administrator::find($userID);

        if (!$user->delete()) {
            $request->session()->flash('error', 'Une errer est survenue lors de la suppression de l\'utilisateur. Veuillez retentez l\'opération.');
            return redirect()->back()->withInput();
        }

        $request->session()->flash('confirmation', trans('Suppression de l\'utilisateur effectuée avec succès'));

        return redirect()->route('user_list');
    }
}