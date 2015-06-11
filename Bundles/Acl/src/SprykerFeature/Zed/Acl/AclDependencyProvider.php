<?php

namespace SprykerFeature\Zed\Acl;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class AclDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_USER = 'user facade';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_USER] = function (Container $container) {
            return $container->getLocator()->user()->facade();
        };

        return $container;
    }

}
