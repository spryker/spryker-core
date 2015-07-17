<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Console\Business\Model\Fixtures;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Zed\Kernel\Communication\AbstractDependencyContainer;
use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Console\Business\Model\Console;

class ConsoleMock extends Console
{

    /**
     * @return AbstractDependencyContainer
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
     * @return AbstractQueryContainer
     */
    public function getQueryContainer()
    {
        return parent::getQueryContainer();
    }

}
