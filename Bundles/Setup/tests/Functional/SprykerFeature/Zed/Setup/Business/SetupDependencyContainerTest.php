<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Setup\Business;

use SprykerEngine\Shared\Config;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Setup\Business\SetupDependencyContainer;
use SprykerFeature\Zed\Setup\SetupConfig;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Setup
 * @group Business
 * @group SetupDependencyContainer
 */
class SetupDependencyContainerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return SetupDependencyContainer
     */
    private function getDependencyContainer()
    {
        $factory = new Factory('Setup');
        $config = new SetupConfig(Config::getInstance(), Locator::getInstance());

        return new SetupDependencyContainer($factory, Locator::getInstance(), $config);
    }

    public function testCreateModelCronjobsShouldReturnFullyConfiguredInstance()
    {
        $instance = $this->getDependencyContainer()->createModelCronjobs();

        $this->assertInstanceOf('SprykerFeature\Zed\Setup\Business\Model\Cronjobs', $instance);
    }

    public function testCreateModelGeneratedDirectoryRemoverShouldReturnFullyConfiguredInstance()
    {
        $instance = $this->getDependencyContainer()->createModelGeneratedDirectoryRemover();

        $this->assertInstanceOf('SprykerFeature\Zed\Setup\Business\Model\DirectoryRemover', $instance);
    }

}
