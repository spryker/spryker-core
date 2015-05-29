<?php

namespace Unit\SprykerFeature\Zed\Setup\Business\Model;

use SprykerEngine\Shared\Config;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Setup\SetupConfig;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Setup
 * @group Business
 * @group SetupConfig
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

    public function testGetPathForJobsPHPShouldReturnPathToJobs()
    {
        $this->assertTrue(is_file($this->getConfig()->getPathForJobsPHP()));
    }

    public function testGetJenkinsUrlShouldReturnString()
    {
        $this->assertTrue(is_string($this->getConfig()->getJenkinsUrl()));
    }

    public function testGetJenkinsDirectoryShouldReturnPathToJenkinsDirectory()
    {
        $this->assertTrue(is_dir($this->getConfig()->getJenkinsDirectory()));
    }

    public function testGetJenkinsJobDirectoryShouldReturnPathToJenkinsJobDirectory()
    {
        $this->assertTrue(is_dir($this->getConfig()->getJenkinsJobsDirectory()));
    }

    public function testGetGeneratedDirectoryShouldReturnPathToGeneratedFiles()
    {
        $this->assertTrue(is_dir($this->getConfig()->getGeneratedDirectory()));
    }

    public function testGetSchemaDirectoryShouldReturnPathToSchemas()
    {
        $this->assertTrue(is_dir($this->getConfig()->getSchemaDirectory()));
    }

    public function testGetPropelSchemaPathPatterShouldReturnArrayWithOnePatternToSchemaDirectories()
    {
        $pathPatterns = $this->getConfig()->getPropelSchemaPathPattern();
        $this->assertTrue(is_array($pathPatterns));
        $this->assertCount(1, $pathPatterns);
    }

    public function testGetPropelSchemaFileNamePatterShouldReturnString()
    {
        $this->assertTrue(is_string($this->getConfig()->getPropelSchemaFileNamePattern()));
    }
}
