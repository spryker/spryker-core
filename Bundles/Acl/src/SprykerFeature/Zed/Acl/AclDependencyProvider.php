<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class AclDependencyProvider extends AbstractBundleDependencyProvider
{

    const FACADE_USER = 'user facade';
    const QUERY_CONTAINER_USER = 'user query container';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addFacadeUser($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addFacadeUser($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
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
     * @return Container
     */
    private function addFacadeUser(Container $container)
    {
        $container[self::FACADE_USER] = function (Container $container) {
            return $container->getLocator()->user()->facade();
        };

        return $container;
    }

}
