<?php

namespace App\Contracts;

interface NotifyInterface
{
    public function send(string $message): void;
}
