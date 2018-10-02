<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl;

use Spryker\Zed\Acl\Dependency\Facade\AclToUserBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class AclDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_USER = 'user facade';
    public const FACADE_ACL = 'acl facade';
    public const QUERY_CONTAINER_USER = 'user query container';
    public const QUERY_CONTAINER_ACL = 'acl query container';
    public const SERVICE_DATE_FORMATTER = 'date formatter service';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addFacadeUser($container);
        $container = $this->addAclQueryContainer($container);

        $container[self::SERVICE_DATE_FORMATTER] = function (Container $container) {
            return $container->getLocator()->utilDateTime()->service();
        };

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
        /** @deprecated Use getQueryContainer() directly for the own bundle's query container */
        $container[self::QUERY_CONTAINER_ACL] = function (Container $container) {
            return $container->getLocator()->acl()->queryContainer();
        };

        return $container;
    }
}
