<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactConfirmation;
use App\Mail\ContactMessage;
use App\Models\Faq;
use App\Models\Promotion;
use App\Models\Solution;
use App\Models\Testimonial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('pages.home', [
            'flagship' => Solution::where('slug', 'menu-digital')->first(),
            'solutions' => Solution::ordered()->get(),
            'testimonials' => Testimonial::active()->get(),
            'faqs' => Faq::active()->get(),
            'promotions' => Promotion::active()->get(),
        ]);
    }

    public function servicios(): View
    {
        return view('pages.servicios', [
            'solutions' => Solution::ordered()->get(),
        ]);
    }

    public function solution(Solution $solution): View
    {
        abort_unless($solution->isAvailable(), 404);

        return view('pages.solution', [
            'solution' => $solution,
            'faqs' => Faq::active()->get(),
        ]);
    }

    public function contacto(): View
    {
        return view('pages.contacto');
    }

    public function enviarContacto(ContactRequest $request): RedirectResponse
    {
        $data = $request->safe()->only([
            'nombre', 'restaurante', 'email', 'telefono', 'mensaje',
        ]);

        // Queue both emails so the request returns immediately — the SMTP work
        // happens in the background worker, never blocking the visitor.
        Mail::to(config('taquerosweb.contact_to'))
            ->queue(new ContactMessage($data));

        Mail::to($data['email'])
            ->queue(new ContactConfirmation($data));

        return redirect()
            ->route('contacto')
            ->with('contacto_enviado', true);
    }

    public function login(): View
    {
        return view('pages.login');
    }

    public function privacy(): View
    {
        return view('pages.legal.privacidad');
    }

    public function terms(): View
    {
        return view('pages.legal.terminos');
    }
}
