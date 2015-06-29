<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\Business;

use SprykerEngine\Zed\Kernel\Container;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Kernel\Business\DependencyContainer\DependencyContainerInterface;
use SprykerEngine\Shared\Kernel\Factory\FactoryInterface;

interface FacadeInterface
{

    /**
     * @param Container $container
     */
    public function setExternalDependencies(Container $container);
}
