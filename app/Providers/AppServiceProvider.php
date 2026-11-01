<?php

namespace App\Providers;

use App\Models\LogAnalyzer;
use App\Models\MalwareScanner;
use App\Models\SecurityScanner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
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

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Store short aliases in loggable_type instead of full class names,
        // and reject any type that is not listed here
        Relation::enforceMorphMap([
            'security_scanner' => SecurityScanner::class,
            'malware_scanner' => MalwareScanner::class,
            'log_analyzer' => LogAnalyzer::class,
        ]);

        // Any lazy-loaded relation throws in dev, so a hidden N+1 cannot slip through
        Model::preventLazyLoading(! $this->app->isProduction());
    }
}
