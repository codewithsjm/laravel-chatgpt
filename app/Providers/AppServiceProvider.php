<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        $this->configureDefaults();

        // Every answer costs API requests: 10 messages a minute, 100 a day, per user.
        RateLimiter::for('chat', function (Request $request) {
            $tooMany = fn () => response()->json([
                'message' => 'You have sent a lot of messages. Take a short break, then try again.',
            ], 429);

            // Each limit needs its own key, or both count on the same counter.
            return [
                Limit::perMinute(10)->by('minute:'.$request->user()->id)->response($tooMany),
                Limit::perDay(100)->by('day:'.$request->user()->id)->response($tooMany),
            ];
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
