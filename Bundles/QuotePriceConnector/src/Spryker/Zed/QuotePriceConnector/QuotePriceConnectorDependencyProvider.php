<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuotePriceConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\QuotePriceConnector\Dependency\Facade\QuotePriceConnectorToPriceFacadeBridge;
use Spryker\Zed\QuotePriceConnector\Dependency\Facade\QuotePriceConnectorToPriceFacadeInterface;

class QuotePriceConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRICE = 'FACADE_PRICE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addPriceFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceFacade(Container $container): Container
    {
        $container[static::FACADE_PRICE] = function (Container $container): QuotePriceConnectorToPriceFacadeInterface {
            return new QuotePriceConnectorToPriceFacadeBridge(
                $container->getLocator()->price()->facade()
            );
        };

        return $container;
    }
}
