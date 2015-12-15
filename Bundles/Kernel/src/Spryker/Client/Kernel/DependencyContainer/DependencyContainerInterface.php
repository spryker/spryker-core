<?php

namespace Spryker\Client\Kernel\DependencyContainer;

use Spryker\Client\Kernel\Container;

interface DependencyContainerInterface
{

    /**
     * @param Container $container
     *
     * @return self
     */
    public function setContainer(Container $container);

}
