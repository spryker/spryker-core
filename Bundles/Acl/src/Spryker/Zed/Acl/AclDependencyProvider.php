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
    const FACADE_ACL = 'acl facade';
    const QUERY_CONTAINER_USER = 'user query container';
    const QUERY_CONTAINER_ACL = 'acl query container';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addFacadeUser($container);
        $container = $this->addAclQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addFacadeUser($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
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
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFacadeUser(Container $container)
    {
        $container[self::FACADE_USER] = function (Container $container) {
            return new AclToUserBridge($container->getLocator()->user()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addAclQueryContainer(Container $container)
    {
        $container[self::QUERY_CONTAINER_ACL] = function (Container $container) {
            return $container->getLocator()->acl()->queryContainer();
        };

        return $container;
    }

}
