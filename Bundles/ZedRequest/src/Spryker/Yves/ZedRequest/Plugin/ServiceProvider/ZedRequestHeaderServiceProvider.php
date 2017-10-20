<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ZedRequest\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\ZedRequest\Client\HandlerStack\HandlerStackContainer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\ZedRequest\ZedRequestFactory getFactory()
 */
class ZedRequestHeaderServiceProvider extends AbstractPlugin implements ServiceProviderInterface
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
        $handlerStackContainer = new HandlerStackContainer();
        $handlerStackContainer->addMiddleware(
            $this->getFactory()->createZedRequestHeaderMiddleware()
        );
    }
}
