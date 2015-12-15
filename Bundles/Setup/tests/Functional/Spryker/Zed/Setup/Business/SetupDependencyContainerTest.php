<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\Setup\Business;

use Spryker\Zed\Setup\Business\SetupDependencyContainer;

/**
 * @group Spryker
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
        return new SetupDependencyContainer();
    }

    /**
     * @return void
     */
    public function testCreateModelCronjobsShouldReturnFullyConfiguredInstance()
    {
        $instance = $this->getDependencyContainer()->createModelCronjobs();

        $this->assertInstanceOf('Spryker\Zed\Setup\Business\Model\Cronjobs', $instance);
    }

    /**
     * @return void
     */
    public function testCreateModelGeneratedDirectoryRemoverShouldReturnFullyConfiguredInstance()
    {
        $instance = $this->getDependencyContainer()->createModelGeneratedDirectoryRemover();

        $this->assertInstanceOf('Spryker\Zed\Setup\Business\Model\DirectoryRemover', $instance);
    }

}
