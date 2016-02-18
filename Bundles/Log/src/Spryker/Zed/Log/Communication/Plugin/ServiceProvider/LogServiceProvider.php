<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Log\Communication\Plugin\ServiceProvider;

use Monolog\Logger;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Log\LogConstants;

class LogServiceProvider implements ServiceProviderInterface
{

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['monolog.level'] = function () {
            return Config::get(LogConstants::LOG_LEVEL, Logger::INFO);
        };
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }


}
