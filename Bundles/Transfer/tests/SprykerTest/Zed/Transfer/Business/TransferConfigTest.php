<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Transfer\Business;

use Codeception\Test\Unit;
use Spryker\Zed\Transfer\TransferConfig;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Transfer
 * @group Business
 * @group TransferConfigTest
 * Add your own group annotations below this line
 */
class TransferConfigTest extends Unit
{
    /**
     * @return \Spryker\Zed\Transfer\TransferConfig
     */
    private function getConfig(): TransferConfig
    {
        return new TransferConfig();
    }

    /**
     * @return void
     */
    public function testGetClassTargetDirectoryShouldReturnString(): void
    {
        $this->assertTrue(is_string($this->getConfig()->getClassTargetDirectory()));
    }

    /**
     * @return void
     */
    public function testGetGeneratedTargetDirectoryShouldReturnString(): void
    {
        $this->assertTrue(is_string($this->getConfig()->getClassTargetDirectory()));
    }

    /**
     * @return void
     */
    public function testGetSourceDirectoriesShouldReturnArray(): void
    {
        $this->assertTrue(is_array($this->getConfig()->getSourceDirectories()));
    }

    /**
     * @return void
     */
    public function testGetSourceDirectoriesShouldReturnArrayWithTwoEntriesIfProjectAndVendorTransferExist(): void
    {
        $directory = APPLICATION_SOURCE_DIR . '/Foo/Shared/Bar/Transfer/';
        mkdir($directory, 0775, true);

        $this->assertTrue(is_array($this->getConfig()->getSourceDirectories()));
        $this->assertCount(2, $this->getConfig()->getSourceDirectories());

        $this->cleanTestDirectories();
    }

    /**
     * @return void
     */
    public function cleanTestDirectories(): void
    {
        $filesystem = new Filesystem();
        $directory = APPLICATION_SOURCE_DIR . '/Foo';
        $filesystem->remove($directory);
    }

    /**
     * @return void
     */
    public function testGetDataBuilderFileNamePatternReturnsString(): void
    {
        $this->assertIsString($this->getConfig()->getDataBuilderFileNamePattern());
    }
}
