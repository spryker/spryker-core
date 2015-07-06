<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Communication\Fixtures\PluginLocator\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerEngine\Zed\Kernel\Communication\DependencyContainer\DependencyContainerInterface;
use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

class Foo extends AbstractPlugin
{

    /**
     * @return DependencyContainerInterface
     */
    public function getDependencyContainerForTests()
    {
        return $this->getDependencyContainer();
    }

    /**
     * @return QueryContainerInterface
     */
    public function getQueryContainerForTests()
    {
        return $this->getQueryContainer();
    }

    /**
     * @return QueryContainerInterface
     */
    public function getFacadeForTests()
    {
        return $this->getFacade();
    }

}
