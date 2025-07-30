<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Models\User;

class PasswordResetController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create()
    {
        return view('landing.auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Check if email exists in users table
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan dalam sistem kami.'
            ]);
        }

        try {
            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.
            $status = Password::sendResetLink(
                $request->only('email')
            );

            \Log::info('Password reset status: ' . $status);

            switch ($status) {
                case Password::RESET_LINK_SENT:
                    return back()->with('status', 'Link reset password telah dikirim ke email Anda!');
                
                case Password::INVALID_USER:
                    return back()->withErrors(['email' => 'Email tidak ditemukan dalam sistem kami.']);
                
                case Password::RESET_THROTTLED:
                    return back()->withErrors(['email' => 'Silakan tunggu sebelum meminta link reset password lagi.']);
                
                default:
                    \Log::error('Password reset failed with status: ' . $status);
                    return back()->withInput($request->only('email'))
                                ->withErrors(['email' => 'Terjadi kesalahan sistem. Detail: ' . __($status)]);
            }
        } catch (\Exception $e) {
            \Log::error('Password reset exception: ' . $e->getMessage());
            return back()->withInput($request->only('email'))
                        ->withErrors(['email' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the password reset view.
     */
    public function edit(Request $request)
    {
        return view('landing.auth.reset-password', ['request' => $request]);
    }

    /**
     * Handle an incoming new password request.
     */
    public function update(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's login screen. If there is an error we can redirect
        // them back to where they came from with their error message.
        return $status == Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', 'Password Anda berhasil direset! Silakan login dengan password baru.')
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}