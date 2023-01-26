<?php

namespace Qebol\Config\Providers;

use Illuminate\Support\ServiceProvider;
use Qebol\Config\Commands\CheckPasswordPolicyExpiry;

class QebolPasswordPolicyServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->commands([
            CheckPasswordPolicyExpiry::class
        ]);
        
        $this->publishes([
            __DIR__.'/../../database/migrations/2023_01_26_101909_create_password_policy_table.php' => database_path('migrations/create_permission_tables.php'),
            __DIR__.'/../../database/migrations/2023_01_26_101926_create_password_changes_table.php' => database_path('migrations/create_password_changes_table.php'),
            __DIR__.'/../../database/migrations/2023_01_26_122505_add_policy_status_to_users_table.php' => database_path('migrations/add_policy_status_to_users_table.php'),
        ], 'password-policy-migrations');
    }
}