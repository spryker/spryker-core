<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel\Communication\Fixtures\AbstractPlugin\Plugin;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Kernel\Communication\DependencyContainer\DependencyContainerInterface;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

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

    /**
     * @return string
     */
    protected function getBundleName()
    {
        return 'Kernel';
    }

}
