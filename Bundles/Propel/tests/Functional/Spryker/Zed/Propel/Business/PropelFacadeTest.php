<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\Propel\Business;

use Spryker\Zed\Propel\Business\PropelFacade;
use Spryker\Zed\Propel\PropelConfig;

/**
 * @group Spryker
 * @group Zed
 * @group Propel
 * @group Business
 * @group PropelFacade
 */
class PropelFacadeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return \Spryker\Zed\Propel\Business\PropelFacade
     */
    private function getFacade()
    {
        return new PropelFacade();
    }

    /**
     * @return \Spryker\Zed\Propel\PropelConfig
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
