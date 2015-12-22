<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel\Communication\Fixtures\PluginLocator\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

class Foo extends AbstractPlugin
{

    /**
     * @return AbstractCommunicationFactory
     */
    public function getFactory()
    {
        return parent::getFactory();
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
