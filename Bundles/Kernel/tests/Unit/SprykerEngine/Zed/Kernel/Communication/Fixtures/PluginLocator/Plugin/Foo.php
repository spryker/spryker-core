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
    public function getDependencyContainer()
    {
        return parent::getDependencyContainer();
    }

    /**
     * @return QueryContainerInterface
     */
    public function getQueryContainer()
    {
        return parent::getQueryContainer();
    }

    /**
     * @return QueryContainerInterface
     */
    public function getFacade()
    {
        return parent::getFacade();
    }

}
