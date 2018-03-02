<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Messenger;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Messenger\Dependency\Client\MessengerToSessionClientBridge;
use Spryker\Client\Messenger\Dependency\Client\MessengerToZedRequestClientBridge;

class MessengerDependencyProvider extends AbstractDependencyProvider
{
    const SERVICE_ZED = 'SERVICE ZED';
    const CLIENT_SESSION = 'SESSION CLIENT';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addSessionClient($container);
        $container = $this->addZedRequest($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSessionClient(Container $container)
    {
        $container[static::CLIENT_SESSION] = function (Container $container) {
            return new MessengerToSessionClientBridge($container->getLocator()->session()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addZedRequest(Container $container): Container
    {
        $container[self::SERVICE_ZED] = function (Container $container) {
            return new MessengerToZedRequestClientBridge($container->getLocator()->zedRequest()->client());
        };

        return $container;
    }
}
