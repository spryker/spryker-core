<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Communication\Fixtures\AbstractPlugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerEngine\Zed\Kernel\Communication\DependencyContainer\DependencyContainerInterface;

class FooPlugin extends AbstractPlugin
{

    /**
     * @return DependencyContainerInterface
     */
    public function getDependencyContainerForTests()
    {
        return $this->getDependencyContainer();
    }

    /**
     * @return DependencyContainerInterface
     */
    public function getQueryContainerForTests()
    {
        return $this->getQueryContainer();
    }
}
