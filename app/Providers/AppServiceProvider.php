<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Gate::define('view-investigation', function (User $user): bool {
            // CHALLENGE 1: Allow only the compliance_officer role (one line).
            return $user->role === 'compliance_officer';
        });
    }
}
