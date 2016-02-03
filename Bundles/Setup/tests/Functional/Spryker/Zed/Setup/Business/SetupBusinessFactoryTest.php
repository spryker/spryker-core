<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\Setup\Business;

use Spryker\Zed\Setup\Business\SetupBusinessFactory;

/**
 * @group Spryker
 * @group Zed
 * @group Setup
 * @group Business
 * @group SetupBusinessFactory
 */
class SetupBusinessFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return \Spryker\Zed\Setup\Business\SetupBusinessFactory
     */
    private function getFactory()
    {
        return new SetupBusinessFactory();
    }

    /**
     * @return void
     */
    public function testCreateModelCronjobsShouldReturnFullyConfiguredInstance()
    {
        $instance = $this->getFactory()->createModelCronjobs();

        $this->assertInstanceOf('Spryker\Zed\Setup\Business\Model\Cronjobs', $instance);
    }

    /**
     * @return void
     */
    public function testCreateModelGeneratedDirectoryRemoverShouldReturnFullyConfiguredInstance()
    {
        $instance = $this->getFactory()->createModelGeneratedDirectoryRemover();

        $this->assertInstanceOf('Spryker\Zed\Setup\Business\Model\DirectoryRemover', $instance);
    }

}
