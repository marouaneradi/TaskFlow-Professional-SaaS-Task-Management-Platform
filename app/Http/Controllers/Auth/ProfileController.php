<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'timezone' => ['nullable', 'string'],
            'theme'    => ['in:light,dark'],
        ]);

        $user->update($request->only('name', 'email', 'timezone', 'theme'));

        if ($user->wasChanged('email')) {
            $user->email_verified_at = null;
            $user->save();
        }

        return back()->with('success', 'Profile updated.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password'         => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (!Hash::check($request->current_password, $request->user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $request->user()->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password updated.');
    }

    public function destroy(Request $request)
    {
        $request->validate(['password' => ['required']]);

        $user = $request->user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Incorrect password.']);
        }

        auth()->logout();
        $user->delete();
        $request->session()->invalidate();

        return redirect('/');
    }
}
