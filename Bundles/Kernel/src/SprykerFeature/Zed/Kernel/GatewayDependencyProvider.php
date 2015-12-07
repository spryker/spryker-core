<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Kernel;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class GatewayDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_FLASH_MESSENGER = 'flash messenger facade';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container[self::FACADE_FLASH_MESSENGER] = function (Container $container) {
            return $container->getLocator()->flashMessenger()->facade();
        };

        return $container;
    }

}
