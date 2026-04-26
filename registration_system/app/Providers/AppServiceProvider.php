<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        if (!app()->runningInConsole()) {
            $this->autoSetup();
        }
    }

    private function autoSetup(): void
    {
        try {
            // Step 1: Create the database if it doesn't exist
            $dbName = config('database.connections.mysql.database');
            config(['database.connections.mysql.database' => '']);
            \Illuminate\Support\Facades\DB::reconnect('mysql');
            \Illuminate\Support\Facades\DB::statement("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            config(['database.connections.mysql.database' => $dbName]);
            \Illuminate\Support\Facades\DB::reconnect('mysql');

            // Step 2: Run migrations if tables are missing
            if (!\Illuminate\Support\Facades\Schema::hasTable('users')) {
                \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
            } elseif (!\App\Models\User::where('role', 'admin')->exists()) {
                \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
            } elseif (!\App\Models\Course::whereNotNull('campus')->where('campus', '!=', '')->exists()) {
                \Illuminate\Support\Facades\Artisan::call('db:seed', [
                    '--class' => 'UniversityDataSeeder',
                    '--force' => true,
                ]);
            }

            $settings = \App\Models\Setting::all()->pluck('value', 'key');
            \Illuminate\Support\Facades\View::share('app_settings', $settings);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\View::share('app_settings', collect());
        }
    }
}
