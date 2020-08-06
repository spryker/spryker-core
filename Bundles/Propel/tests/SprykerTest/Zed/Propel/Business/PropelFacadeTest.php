<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Propel\Business;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Propel
 * @group Business
 * @group Facade
 * @group PropelFacadeTest
 * Add your own group annotations below this line
 */
class PropelFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Propel\PropelBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testCleanPropelSchemaDirectoryShouldRemoveSchemaDirectoryAndAllFilesInIt(): void
    {
        $schemaDirectory = $this->tester->getVirtualDirectory();

        $this->tester->mockConfigMethod('getSchemaDirectory', function () use ($schemaDirectory) {
            return $schemaDirectory;
        });

        $this->assertTrue(is_dir($schemaDirectory));
        $this->tester->getFacade()->cleanPropelSchemaDirectory();
        $this->assertFalse(is_dir($schemaDirectory));
    }

    /**
     * @return void
     */
    public function testCopySchemaFilesToTargetDirectoryShouldCollectAllSchemaFilesMergeAndCopyThemToSpecifiedDirectory(): void
    {
        $schemaDirectory = $this->tester->getVirtualDirectory();

        $this->tester->mockConfigMethod('getSchemaDirectory', function () use ($schemaDirectory) {
            return $schemaDirectory;
        });

        $this->tester->getFacade()->copySchemaFilesToTargetDirectory();
        $this->assertTrue(is_dir($schemaDirectory));
    }
}
