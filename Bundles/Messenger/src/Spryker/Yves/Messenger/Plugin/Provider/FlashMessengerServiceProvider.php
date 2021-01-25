<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Messenger\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessenger;

/**
 * @deprecated Use {@link \Spryker\Yves\Messenger\Plugin\Provider\FlashMessengerApplicationPlugin} instead.
 *
 * @see \Spryker\Yves\Messenger\Plugin\Provider\FlashMessengerApplicationPlugin
 */
class FlashMessengerServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['flash_messenger'] = function ($app) {
            return $this->createFlashMessenger($app);
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

    /**
     * @param \Silex\Application $app
     *
     * @return \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    protected function createFlashMessenger(Application $app)
    {
        return new FlashMessenger($app['session']->getFlashBag());
    }
}
