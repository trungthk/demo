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
        $root_data = 'I’ve been using the Logitech MX Master 3S for over a month now, 

and I can confidently say it’s the best mouse I’ve ever owned.

The ergonomic design fits perfectly in my hand, making long hours of work much more comfortable.
The build quality feels premium, with a soft-touch finish that doesn’t get slippery even during extended use.

One of my favorite features is the MagSpeed scroll wheel — it’s incredibly smooth and allows me to switch between precise scrolling and fast free-spinning with ease.
The side scroll wheel is also a game-changer for navigating through timelines in video editing software.';
        $data = $root_data; 
        return view('index', compact('data'));
    }
}
