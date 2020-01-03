<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Messenger\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @deprecated Use `\Spryker\Zed\Messenger\Communication\Plugin\Application\MessengerApplicationPlugin` instead.
 *
 * @method \Spryker\Zed\Messenger\Business\MessengerFacadeInterface getFacade()
 * @method \Spryker\Zed\Messenger\MessengerConfig getConfig()
 */
class MessengerServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['messenger'] = function () {
            return $this->getFacade();
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
