<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Transfer\Business;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Transfer\TransferConfig;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Transfer
 * @group Business
 * @group TransferConfigTest
 * Add your own group annotations below this line
 */
class TransferConfigTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return \Spryker\Zed\Transfer\TransferConfig
     */
    private function getConfig()
    {
        return new TransferConfig();
    }

    /**
     * @return void
     */
    public function testGetClassTargetDirectoryShouldReturnString()
    {
        $this->assertTrue(is_string($this->getConfig()->getClassTargetDirectory()));
    }

    /**
     * @return void
     */
    public function testGetGeneratedTargetDirectoryShouldReturnString()
    {
        $this->assertTrue(is_string($this->getConfig()->getClassTargetDirectory()));
    }

    /**
     * @return void
     */
    public function testGetSourceDirectoriesShouldReturnArray()
    {
        $this->assertTrue(is_array($this->getConfig()->getSourceDirectories()));
    }

    /**
     * @return void
     */
    public function testGetSourceDirectoriesShouldReturnArrayWithTwoEntriesIfProjectAndVendorTransferExist()
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
    public function cleanTestDirectories()
    {
        $filesystem = new Filesystem();
        $directory = APPLICATION_SOURCE_DIR . '/Foo';
        $filesystem->remove($directory);
    }

    /**
     * @return void
     */
    public function testGetDataBuilderFileNamePatternReturnsString()
    {
        $this->assertInternalType('string', $this->getConfig()->getDataBuilderFileNamePattern());
    }

}
