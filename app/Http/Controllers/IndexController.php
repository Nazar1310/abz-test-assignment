<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class IndexController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view('index');
    }
}
