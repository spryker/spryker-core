<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Persistence\DependencyContainer;

use SprykerEngine\Zed\Kernel\Container;

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
