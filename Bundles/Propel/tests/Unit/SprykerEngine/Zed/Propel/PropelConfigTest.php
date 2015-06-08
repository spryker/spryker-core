<?php

namespace Unit\SprykerEngine\Zed\Propel;

use SprykerEngine\Shared\Config;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerEngine\Zed\Propel\PropelConfig;

class PropelConfigTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return PropelConfig
     */
    private function getConfig()
    {
        return new PropelConfig(Config::getInstance(), $this->getLocator());
    }

    /**
     * @return Locator
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

    public function testGetGeneratedDirectoryShouldReturnPathToGeneratedFiles()
    {
        $this->assertTrue(is_dir($this->getConfig()->getGeneratedDirectory()));
    }

    public function testGetSchemaDirectoryShouldReturnPathToSchemas()
    {
        $this->assertTrue(is_dir($this->getConfig()->getSchemaDirectory()));
    }

    public function testGetPropelSchemaPathPatterShouldReturnArrayWithPatternsToSchemaDirectories()
    {
        $pathPatterns = $this->getConfig()->getPropelSchemaPathPatterns();
        $this->assertTrue(is_array($pathPatterns));
    }

}
