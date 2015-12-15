<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Persistence\DependencyContainer;

use Spryker\Zed\Kernel\Container;

interface DependencyContainerInterface
{

    /**
     * @param Container $container
     */
    public function setContainer(Container $container);

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getProvidedDependency($key);

}
