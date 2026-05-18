<?php


namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {if (!auth()->user()->admin) {
        abort(403, 'Acesso restrito a administradores.');
    }
        // Só permite se for admin
        if (!auth()->user()->admin) {
            abort(403, 'Acesso negado.');
        }

        $users = User::get();

        return Inertia::render('Users/Index', [
            'users' => $users
        ]);
    }
}