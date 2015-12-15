<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\User;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class UserDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_ACL = 'facade acl';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container[self::FACADE_ACL] = function (Container $container) {
            return $container->getLocator()->acl()->facade();
        };

        return $container;
    }

}
