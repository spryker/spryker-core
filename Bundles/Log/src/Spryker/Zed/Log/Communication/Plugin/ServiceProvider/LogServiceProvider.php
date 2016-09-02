<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Log\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Application\EventListener\KernelLogListener;
use Spryker\Shared\Log\LoggerFactory;

/**
 * @deprecated Use KernelLogServiceProvider instead.
 */
class LogServiceProvider implements ServiceProviderInterface
{

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
        $this->getDispatcher($app)->addSubscriber(
            new KernelLogListener(LoggerFactory::getInstance())
        );
    }

    /**
     * @param \Silex\Application $app
     *
     * @return \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected function getDispatcher(Application $app)
    {
        return $app['dispatcher'];
    }
}
