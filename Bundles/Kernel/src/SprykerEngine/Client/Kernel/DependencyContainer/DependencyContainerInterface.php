<?php

namespace SprykerEngine\Client\Kernel\DependencyContainer;

use SprykerEngine\Client\Kernel\Container;

interface DependencyContainerInterface
{

    /**
     * @param Container $container
     *
     * @return $this
     */
    public function setContainer(Container $container);

}
