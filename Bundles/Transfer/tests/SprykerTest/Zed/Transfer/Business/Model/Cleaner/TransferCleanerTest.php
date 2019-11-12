<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Transfer\Business\Model\Cleaner;

use Codeception\Test\Unit;
use Spryker\Zed\Transfer\Business\Model\TransferCleaner;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Transfer
 * @group Business
 * @group Model
 * @group Cleaner
 * @group TransferCleanerTest
 * Add your own group annotations below this line
 */
class TransferCleanerTest extends Unit
{
    public const TEST_FILE_NAME = 'TestTransfer.php';

    /**
     * @return void
     */
    public function setUp(): void
    {
        $testDirectory = $this->getTestDirectory();
        if (!is_dir($testDirectory)) {
            mkdir($testDirectory, 0775, true);
        }

        file_put_contents($testDirectory . static::TEST_FILE_NAME, '');
    }

    /**
     * @return string
     */
    private function getTestDirectory()
    {
        return codecept_data_dir('test_files/tmp/');
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        $testFile1 = $this->getTestDirectory() . static::TEST_FILE_NAME;
        if (file_exists($testFile1)) {
            unlink($testFile1);
        }

        if (is_dir($this->getTestDirectory())) {
            rmdir($this->getTestDirectory());
        }
    }

    /**
     * @return void
     */
    public function testExecuteShouldDeleteAllFilesInADirectory()
    {
        $this->assertTrue(file_exists($this->getTestDirectory() . static::TEST_FILE_NAME));

        $cleaner = new TransferCleaner($this->getTestDirectory());
        $cleaner->cleanDirectory();

        $this->assertFalse(file_exists($this->getTestDirectory() . static::TEST_FILE_NAME));
    }
}
