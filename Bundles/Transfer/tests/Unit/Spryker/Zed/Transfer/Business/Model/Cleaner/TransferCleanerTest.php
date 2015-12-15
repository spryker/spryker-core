<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Transfer\Business\Model\Cleaner;

use Spryker\Zed\Transfer\Business\Model\TransferCleaner;

/**
 * @group Spryker
 * @group Zed
 * @group Transfer
 * @group Business
 * @group Cleaner
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
            mkdir($testDirectory, 0777, true);
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
