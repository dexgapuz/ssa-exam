<?php

namespace App\Listeners;

use App\Events\UserSaved;
use App\Services\UserService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SaveUserBackgroundInformation
{
    public UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Handle the event.
     */
    public function handle(UserSaved $event): void
    {
        $this->userService->saveDetails($event->user);
    }
}
