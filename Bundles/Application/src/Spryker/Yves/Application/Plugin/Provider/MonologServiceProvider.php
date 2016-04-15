<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Application\Plugin\Provider;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Library\Log;
use Spryker\Shared\Library\Monolog\EventJournalHandler;

/**
 * @deprecated Use Spryker\Shared\Log\LoggerTrait where ever you need to log something with monolog
 * instead of using the silex monolog service.
 */
class MonologServiceProvider implements ServiceProviderInterface
{

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['logger'] = function () use ($app) {
            return $app['monolog'];
        };

        $app['monolog.logger.class'] = 'Monolog\Logger';

        $app['monolog'] = $app->share(function ($app) {
            $log = new $app['monolog.logger.class']($app['monolog.name']);

            $log->pushHandler($app['monolog.handler']);

            if ($app['debug']) {
                $log->pushHandler($app['monolog.handler.debug']);
            }

            return $log;
        });

        $app['monolog.logfile'] = function () {
            return Log::getFilePath('message.log');
        };

        $app['monolog.handler.debug'] = function () use ($app) {
            return new StreamHandler($app['monolog.logfile'], $app['monolog.level']);
        };

        $app['monolog.handler'] = function () use ($app) {
            return new EventJournalHandler($app['monolog.level']);
        };

        $app['monolog.level'] = function () {
            return Logger::INFO;
        };

        $app['monolog.name'] = 'yves';
    }

    /**
     * @codeCoverageIgnore
     *
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }

}
