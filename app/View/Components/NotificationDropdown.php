<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class NotificationDropdown extends Component
{
    public $notifications;
    public $unreadCount;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        $user = Auth::user();
        if ($user) {
            $this->notifications = $user->unreadNotifications->take(5);
            $this->unreadCount = $user->unreadNotifications->count();
        } else {
            $this->notifications = collect();
            $this->unreadCount = 0;
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.notification-dropdown');
    }
}
