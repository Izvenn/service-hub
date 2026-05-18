<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Company;
use App\Models\Project;

class RegisteredUserController extends Controller
{
    public function create(): Response
    {
        // Enviamos empresas e projetos para popular os selects no Vue
        return Inertia::render('Auth/Register', [
            'companies' => Company::with('projects')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => 'required|string|max:20',
            'position' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'project_id' => 'required|exists:projects,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'admin' => false, 
        ]);

        $user->profile()->create([
            'company_id' => $request->company_id,
            'project_id' => $request->project_id,
            'phone' => $request->phone,
            'position' => $request->position,
            'is_responsible' => false,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('tickets.index', absolute: false));
    }
}