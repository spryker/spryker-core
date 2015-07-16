<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\Communication\Fixtures\AbstractPlugin\Plugin;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerEngine\Zed\Kernel\Communication\DependencyContainer\DependencyContainerInterface;
use SprykerEngine\Zed\Kernel\Communication\Factory;
use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

class FooPlugin extends AbstractPlugin
{

    /**
     * @return DependencyContainerInterface
     */
    public function getDependencyContainer()
    {
        return parent::getDependencyContainer();
    }

    /**
     * @return AbstractFacade
     */
    public function getFacade()
    {
        return parent::getFacade();
    }

    /**
     * @return QueryContainerInterface
     */
    public function getQueryContainer()
    {
        return parent::getQueryContainer();
    }

}
