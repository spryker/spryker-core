<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Setup\Business;

use SprykerFeature\Zed\Setup\Business\SetupDependencyContainer;

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
        return new SetupDependencyContainer();
    }

    /**
     * @return void
     */
    public function testCreateModelCronjobsShouldReturnFullyConfiguredInstance()
    {
        $instance = $this->getDependencyContainer()->createModelCronjobs();

        $this->assertInstanceOf('SprykerFeature\Zed\Setup\Business\Model\Cronjobs', $instance);
    }

    /**
     * @return void
     */
    public function testCreateModelGeneratedDirectoryRemoverShouldReturnFullyConfiguredInstance()
    {
        $instance = $this->getDependencyContainer()->createModelGeneratedDirectoryRemover();

        $this->assertInstanceOf('SprykerFeature\Zed\Setup\Business\Model\DirectoryRemover', $instance);
    }

}
