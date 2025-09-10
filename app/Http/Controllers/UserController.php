<?php

namespace App\Http\Controllers;

use App\Contracts\NotifyInterface;
use App\Models\User;

class UserController extends Controller
{
    public $notifyService;

    public function __construct(NotifyInterface $notifyService = null)
    {
        $this->notifyService = $notifyService;
    }

    public function index() {
        $this->notifyService->send("Hello from UserController");
    }

    public function show(User $user) {
        return $user;
    }
}
