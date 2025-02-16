<?php

namespace App\Services;

interface UserServiceInterface
{
    public function hash(string $key): string;
}
