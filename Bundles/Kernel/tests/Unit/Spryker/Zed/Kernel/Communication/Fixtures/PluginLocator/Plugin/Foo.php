<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel\Communication\Fixtures\PluginLocator\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Kernel\Communication\DependencyContainer\DependencyContainerInterface;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

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
