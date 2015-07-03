<?php

namespace SprykerEngine\Client\Kernel;

interface DependencyProviderInterface
{

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideServiceLayerDependencies(Container $container);

}
