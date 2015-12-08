<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerEngine\Zed\Propel\Business;

use SprykerEngine\Zed\Propel\Business\PropelFacade;
use SprykerEngine\Zed\Propel\PropelConfig;

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
        return new PropelFacade();
    }

    /**
     * @return PropelConfig
     */
    private function getConfig()
    {
        return new PropelConfig();
    }

    /**
     * @return void
     */
    public function testCleanPropelSchemaDirectoryShouldRemoveSchemaDirectoryAndAllFilesInIt()
    {
        $this->assertTrue(is_dir($this->getConfig()->getSchemaDirectory()));
        $this->getFacade()->cleanPropelSchemaDirectory();
        $this->assertFalse(is_dir($this->getConfig()->getSchemaDirectory()));
    }

    /**
     * @return void
     */
    public function testCopySchemaFilesToTargetDirectoryShouldCollectAllSchemaFilesMergeAndCopyThemToSpecifiedDirectory()
    {
        $this->assertFalse(is_dir($this->getConfig()->getSchemaDirectory()));
        $this->getFacade()->copySchemaFilesToTargetDirectory();
        $this->assertTrue(is_dir($this->getConfig()->getSchemaDirectory()));
    }

}
