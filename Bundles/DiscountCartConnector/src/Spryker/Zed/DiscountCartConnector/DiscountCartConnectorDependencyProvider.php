<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountCartConnector;

use Spryker\Zed\DiscountCartConnector\Dependency\Facade\DiscountCartConnectorToMessengerFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class DiscountCartConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_MESSENGER = 'FACADE_MESSENGER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addMessengerFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessengerFacade(Container $container): Container
    {
        $container[static::FACADE_MESSENGER] = function (Container $container) {
            return new DiscountCartConnectorToMessengerFacadeBridge($container->getLocator()->messenger()->facade());
        };

        return $container;
    }
}
