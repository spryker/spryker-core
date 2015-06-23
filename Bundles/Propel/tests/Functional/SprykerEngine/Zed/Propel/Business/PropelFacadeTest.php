<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerEngine\Zed\Propel\Business;

use SprykerEngine\Zed\Propel\Business\PropelFacade;
use SprykerEngine\Zed\Kernel\Business\Factory;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Propel\PropelConfig;
use SprykerEngine\Shared\Config;

/**
 * @group SprykerEngine
 * @group Zed
 * @group Propel
 * @group Business
 * @group PropelFacade
 */
class PropelFacadeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return PropelFacade
     */
    private function getFacade()
    {
        $factory = new Factory('Propel');

        return new PropelFacade($factory, $this->getLocator());
    }

    /**
     * @return Locator
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

    /**
     * @return PropelConfig
     */
    private function getConfig()
    {
        return new PropelConfig(Config::getInstance(), $this->getLocator());
    }

    public function testCleanPropelSchemaDirectoryShouldRemoveSchemaDirectoryAndAllFilesInIt()
    {
        $this->assertTrue(is_dir($this->getConfig()->getSchemaDirectory()));
        $this->getFacade()->cleanPropelSchemaDirectory();
        $this->assertFalse(is_dir($this->getConfig()->getSchemaDirectory()));
    }

    public function testCopySchemaFilesToTargetDirectoryShouldCollectAllSchemaFilesMergeAndCopyThemToSpecifiedDirectory()
    {
        $this->assertFalse(is_dir($this->getConfig()->getSchemaDirectory()));
        $this->getFacade()->copySchemaFilesToTargetDirectory();
        $this->assertTrue(is_dir($this->getConfig()->getSchemaDirectory()));
    }

}
