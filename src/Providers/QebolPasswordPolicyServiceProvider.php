<?php

namespace Qebol\Config\Providers;

use Illuminate\Support\ServiceProvider;

class QebolPasswordPolicyServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->publishes([
            __DIR__.'/../../database/migrations/2023_01_26_101909_create_password_policy_table.php' => database_path('migrations/create_permission_tables.php'),
            __DIR__.'/../../database/migrations/2023_01_26_101926_create_password_changes_table.php' => database_path('migrations/create_password_changes_table.php'),
        ], 'password-policy-migrations');

    }
}