<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
        if (!is_dir($this->getConfig()->getSchemaDirectory())) {
            mkdir($this->getConfig()->getSchemaDirectory(), 755, true);
        }

        $this->assertTrue(is_dir($this->getConfig()->getSchemaDirectory()));
        $this->getFacade()->cleanPropelSchemaDirectory();
        $this->assertFalse(is_dir($this->getConfig()->getSchemaDirectory()));
    }

    /**
     * @return void
     */
    public function testCopySchemaFilesToTargetDirectoryShouldCollectAllSchemaFilesMergeAndCopyThemToSpecifiedDirectory()
    {
        $this->getFacade()->copySchemaFilesToTargetDirectory();
        $this->assertTrue(is_dir($this->getConfig()->getSchemaDirectory()));
    }

}
