<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Acl;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Acl\Dependency\Facade\AclToUserBridge;

class AclDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_USER = 'user facade';
    const QUERY_CONTAINER_USER = 'user query container';

    /**
     * @param Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addFacadeUser($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addFacadeUser($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container[self::QUERY_CONTAINER_USER] = function (Container $container) {
            return $container->getLocator()->user()->queryContainer();
        };

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function addFacadeUser(Container $container)
    {
        $container[self::FACADE_USER] = function (Container $container) {
            return new AclToUserBridge($container->getLocator()->user()->facade());
        };

        return $container;
    }

}
