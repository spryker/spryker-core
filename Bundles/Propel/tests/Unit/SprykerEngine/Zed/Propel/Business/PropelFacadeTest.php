<?php

namespace Unit\SprykerEngine\Zed\Propel\Business;

use SprykerEngine\Shared\Config;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Propel\Business\PropelFacade;
use SprykerEngine\Zed\Propel\PropelConfig;

class PropelFacadeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return PropelConfig
     */
    private function getConfig()
    {
        return new PropelConfig(Config::getInstance(), $this->getLocator());
    }

    /**
     * @return PropelFacade
     */
    private function getFacade()
    {
        return new PropelFacade(new Factory('Propel'), $this->getLocator());
    }

    /**
     * @return Locator
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

}
