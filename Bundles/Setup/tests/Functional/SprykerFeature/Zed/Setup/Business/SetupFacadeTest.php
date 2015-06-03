<?php

namespace Functional\SprykerFeature\Zed\Setup\Business;

use SprykerFeature\Zed\Setup\Business\SetupFacade;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Setup\SetupConfig;
use SprykerEngine\Shared\Config;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Setup
 * @group Business
 * @group SetupFacade
 */
class SetupFacadeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return SetupFacade
     */
    private function getFacade()
    {
        $factory = new Factory('Setup');

        return new SetupFacade($factory, $this->getLocator());
    }

    /**
     * @return Locator
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

    /**
     * @return SetupConfig
     */
    private function getConfig()
    {
        return new SetupConfig(Config::getInstance(), $this->getLocator());
    }

}
