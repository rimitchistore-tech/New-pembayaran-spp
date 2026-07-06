<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    /**
     * Show email verification notice.
     */
    public function notice()
    {
        return auth()->user()->hasVerifiedEmail() 
            ? redirect('/dashboard') 
            : view('auth.verify-email');
    }

    /**
     * Mark email as verified.
     */
    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/dashboard')->with('status', 'Email sudah terverifikasi.');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect('/dashboard')->with('status', 'Email berhasil diverifikasi!');
    }

    /**
     * Resend verification email.
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'Email verifikasi sudah dikirim ulang.');
    }
}
