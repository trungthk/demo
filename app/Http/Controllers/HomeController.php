<?php

namespace App\Http\Controllers;

use App\Contracts\NotifyInterface;

class HomeController extends Controller
{
    public $notifyService;

    public function __construct(NotifyInterface $notifyService = null)
    {
        $this->notifyService = $notifyService;
    }

    public function test() {
        return view('test');
    }
    
    public function index() {
        // $this->notifyService->send("Hello from HomeController");
        $root_data = '😁😁😁😁😁😁😁😁😁😁😁😁😁😁😁 😁😁😁😁😁😁😁😁😁😁😁😁😁😁😁😁😁😁😁😁😁😁😁😁😁😁😁😁😁😁';
    $data = nl2br(e($root_data)); 
        return view('index', compact('data'));
    }
}
