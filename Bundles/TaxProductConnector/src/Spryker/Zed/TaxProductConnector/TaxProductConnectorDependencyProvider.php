<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\TaxProductConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\TaxProductConnector\Dependency\Facade\TaxProductConnectorToProductBridge;

class TaxProductConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_PRODUCT = 'facade product';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container[self::FACADE_PRODUCT] = function (Container $container) {
            return new TaxProductConnectorToProductBridge($container->getLocator()->product()->facade());
        };

        return $container;
    }

}
