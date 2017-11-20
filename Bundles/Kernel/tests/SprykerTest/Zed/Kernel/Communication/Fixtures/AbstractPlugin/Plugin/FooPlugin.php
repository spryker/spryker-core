<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerTest\Zed\Kernel\Communication\Fixtures\AbstractPlugin\Plugin;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;

class FooPlugin extends AbstractPlugin
{
    /**
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    public function getFactory()
    {
        return parent::getFactory();
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    public function getFacade()
    {
        return parent::getFacade();
    }

    /**
     * @return \Spryker\Zed\Kernel\Persistence\AbstractQueryContainer
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
