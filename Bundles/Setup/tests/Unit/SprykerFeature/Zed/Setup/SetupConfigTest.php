<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Setup;

use Spryker\Shared\Config;
use Spryker\Zed\Kernel\Locator;
use Spryker\Zed\Setup\SetupConfig;

/**
 * @group Spryker
 * @group Zed
 * @group Setup
 * @group Business
 * @group SetupConstants
 */
class SetupConfigTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return SetupConfig
     */
    private function getConfig()
    {
        return new SetupConfig(Config::getInstance(), Locator::getInstance());
    }

    /**
     * @return void
     */
    public function testGetPathForJobsPHPShouldReturnPathToJobs()
    {
        $this->assertTrue(is_file($this->getConfig()->getPathForJobsPHP()));
    }

    /**
     * @return void
     */
    public function testGetJenkinsUrlShouldReturnString()
    {
        $this->assertTrue(is_string($this->getConfig()->getJenkinsUrl()));
    }

    /**
     * @return void
     */
    public function testGetJenkinsDirectoryShouldReturnPathToJenkinsDirectory()
    {
        $this->assertTrue(is_dir($this->getConfig()->getJenkinsDirectory()));
    }

    /**
     * @return void
     */
    public function testGetJenkinsJobDirectoryShouldReturnPathToJenkinsJobDirectory()
    {
        $this->assertTrue(is_dir($this->getConfig()->getJenkinsJobsDirectory()));
    }

    /**
     * @return void
     */
    public function testGetGeneratedDirectoryShouldReturnPathToGeneratedFiles()
    {
        $this->assertTrue(is_dir($this->getConfig()->getGeneratedDirectory()));
    }

    /**
     * @return void
     */
    public function testGetSetupInstallCommandNamesMustReturnArray()
    {
        $this->assertInternalType('array', $this->getConfig()->getSetupInstallCommandNames());
    }

}
