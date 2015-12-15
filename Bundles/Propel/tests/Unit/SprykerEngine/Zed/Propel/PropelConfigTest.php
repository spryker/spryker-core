<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Propel;

use Spryker\Shared\Config;
use Spryker\Zed\Kernel\Locator;
use Spryker\Zed\Propel\PropelConfig;

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
    public function testGetSchemaDirectoryShouldReturnPathToSchemas()
    {
        $this->assertTrue(is_dir($this->getConfig()->getSchemaDirectory()));
    }

    /**
     * @return void
     */
    public function testGetPropelSchemaPathPatterShouldReturnArrayWithPatternsToSchemaDirectories()
    {
        $pathPatterns = $this->getConfig()->getPropelSchemaPathPatterns();
        $this->assertTrue(is_array($pathPatterns));
    }

}
