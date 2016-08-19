<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Transfer\Business\Model\Cleaner;

use Spryker\Zed\Transfer\Business\Model\TransferCleaner;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Transfer
 * @group Business
 * @group Model
 * @group Cleaner
 * @group TransferCleanerTest
 */
class TransferCleanerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function setUp()
    {
        $testDirectory = $this->getTestDirectory();
        if (!is_dir($testDirectory)) {
            mkdir($testDirectory, 0775, true);
        }

        file_put_contents($testDirectory . 'file', '');
    }

    /**
     * @return string
     */
    private function getTestDirectory()
    {
        return __DIR__ . '/Fixtures/';
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        $testFile1 = $this->getTestDirectory() . 'file';
        if (file_exists($testFile1)) {
            unlink($testFile1);
        }
    }

    /**
     * @return void
     */
    public function testExecuteShouldDeleteAllFilesInADirectory()
    {
        $this->assertTrue(file_exists($this->getTestDirectory() . 'file'));

        $cleaner = new TransferCleaner($this->getTestDirectory());
        $cleaner->cleanDirectory();

        $this->assertFalse(file_exists($this->getTestDirectory() . 'file'));
    }

}
