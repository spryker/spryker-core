<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace SprykerFeature\Zed\PayolutionOmsConnector;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class PayolutionOmsConnectorDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_PAYOLUTION = 'facade payolution';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_PAYOLUTION] = function (Container $container) {
            return $container->getLocator()->payolution()->facade();
        };

        return $container;
    }

}
