<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 🔒 সেশন শেষ হলে ফ্ল্যাশ মেসেজসহ Owner Login-এ রিডাইরেক্ট
        $middleware->redirectGuestsTo(function (Request $request) {
            session()->flash('session_expired', 'আপনার সেশনের সময় শেষ হয়ে গেছে। অনুগ্রহ করে আবার লগইন করুন।');
            return route('owner.login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
