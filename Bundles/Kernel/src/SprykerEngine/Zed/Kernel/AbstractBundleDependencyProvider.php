<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel;

abstract class AbstractBundleDependencyProvider implements BundleDependencyProviderInterface
{

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        return $container;
    }

}
