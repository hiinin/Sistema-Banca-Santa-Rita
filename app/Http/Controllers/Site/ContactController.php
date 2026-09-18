<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Display the contact and location page of Banca Santa Rita.
     */
    public function index(): View
    {
        $config = Configuration::current();

        return view('site.contact', compact('config'));
    }
}
