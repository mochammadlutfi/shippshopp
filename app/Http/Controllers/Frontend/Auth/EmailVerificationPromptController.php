<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request)
    {
        if($request->user()->hasVerifiedEmail()){
            return redirect()->intended(RouteServiceProvider::HOME);
        }else{
            return Inertia::render('Auth/Verify');
        }
    }
}
