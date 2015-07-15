<?php

namespace SprykerEngine\Client\Kernel;

abstract class AbstractDependencyProvider implements DependencyProviderInterface
{

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        return $container;
    }

}
