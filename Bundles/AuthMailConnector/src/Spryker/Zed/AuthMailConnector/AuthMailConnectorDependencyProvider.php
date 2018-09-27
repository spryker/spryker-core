<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AuthMailConnector;

use Spryker\Zed\AuthMailConnector\Dependency\Facade\AuthMailConnectorToMailBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class AuthMailConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_MAIL = 'mail facade';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_MAIL] = function (Container $container) {
            return new AuthMailConnectorToMailBridge($container->getLocator()->mail()->facade());
        };

        return $container;
    }
}
