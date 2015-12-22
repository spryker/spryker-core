<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Transfer\Business;

use Spryker\Shared\Config;
use Spryker\Zed\Transfer\TransferConfig;
use Symfony\Component\Filesystem\Filesystem;

class TransferConfigTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return TransferConfig
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
        $this->assertTrue(is_string($this->getConfig()->getGeneratedTargetDirectory()));
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
        mkdir($directory, 0777, true);

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

}
