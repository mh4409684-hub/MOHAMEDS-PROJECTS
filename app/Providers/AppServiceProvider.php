<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
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
        if (request()->server->has('HTTP_X_FORWARDED_PROTO') && request()->server->get('HTTP_X_FORWARDED_PROTO') === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        } elseif (str_contains(request()->header('host', ''), 'trycloudflare.com') || str_contains(request()->header('host', ''), 'render.com')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Configure default stream SSL context for Symfony Mailer SMTP sockets
        app('mail.manager')->extend('smtp', function (array $config) {
            $factory = new \Symfony\Component\Mailer\Transport\Smtp\EsmtpTransportFactory();
            $scheme = $config['scheme'] ?? (($config['port'] == 465) ? 'smtps' : 'smtp');
            
            $transport = $factory->create(new \Symfony\Component\Mailer\Transport\Dsn(
                $scheme,
                $config['host'],
                $config['username'] ?? null,
                $config['password'] ?? null,
                $config['port'] ?? null,
                $config
            ));

            $stream = $transport->getStream();
            if ($stream instanceof \Symfony\Component\Mailer\Transport\Smtp\Stream\SocketStream) {
                $stream->setStreamOptions([
                    'ssl' => [
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true,
                    ]
                ]);
            }

            return $transport;
        });

        $this->configureDefaults();
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
