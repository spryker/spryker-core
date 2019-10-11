<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Monitoring\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @deprecated Use `\Spryker\Yves\Monitoring\Plugin\EventDispatcher\MonitoringRequestTransactionEventDispatcherPlugin` instead.
 *
 * @method \Spryker\Yves\Monitoring\MonitoringFactory getFactory()
 */
class MonitoringRequestTransactionServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app): void
    {
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app): void
    {
        $this->addControllerListener($app);
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function addControllerListener(Application $app): void
    {
        $this->getDispatcher($app)->addSubscriber(
            $this->getFactory()->createControllerListener()
        );
    }

    /**
     * @param \Silex\Application $app
     *
     * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected function getDispatcher(Application $app): EventDispatcherInterface
    {
        return $app['dispatcher'];
    }
}
