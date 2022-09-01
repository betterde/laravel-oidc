<?php

namespace Package\Oidc\Providers;

use Package\Oidc\OIDCGuard;
use Lcobucci\JWT\Token\Parser;
use Package\Oidc\OIDCUserProvider;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Illuminate\Support\ServiceProvider;

/**
 * Date: 2022/9/1
 * @author George
 * @package Package\Oidc
 */
class OIDCServiceProvider extends ServiceProvider
{
    /**
     * Date: 2022/9/1
     * @author George
     */
    public function boot()
    {
        $path = realpath(__DIR__ . '/../../config/oidc.php');

        $this->publishes([$path => $this->app->configPath('oidc.php')], 'oidc:config');

        $this->mergeConfigFrom(__DIR__ . '/../../config/oidc.php', 'oidc');

        $this->app['auth']->extend('oidc', function ($app, $name, array $config) {
            $guard = new OIDCGuard($app['auth']->createUserProvider($config['provider']), new Parser(new JoseEncoder()),$app['request']);

            $app->refresh('request', $guard, 'setRequest');

            return $guard;
        });

        Auth::provider('oidc', function () {
            return new OIDCUserProvider();
        });
    }
}