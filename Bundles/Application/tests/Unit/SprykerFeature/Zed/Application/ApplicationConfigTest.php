<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Application;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Config;
use SprykerEngine\Zed\Kernel\Locator;
use SprykerFeature\Zed\Application\ApplicationConfig;

class ApplicationConfigTest extends \PHPUnit_Framework_TestCase
{

    private function getConfig()
    {
        return new ApplicationConfig(Config::getInstance(), $this->getLocator());
    }

    /**
     * @return \SprykerEngine\Shared\Kernel\AbstractLocatorLocator|static|AutoCompletion
     */
    private function getLocator()
    {
        return Locator::getInstance();
    }

    /**
     * @return void
     */
    public function testGetCheckStepsShouldReturnArray()
    {
        $this->assertInternalType('array', $this->getConfig()->getCheckSteps());
    }

    /**
     * @return void
     */
    public function testGetMaxMenuLevelCountShouldReturnInteger()
    {
        $this->assertInternalType('integer', $this->getConfig()->getMaxMenuLevelCount());
    }

    /**
     * @return void
     */
    public function testGetNavigationSchemaPathPatternShouldReturnArrayWithOneEntry()
    {
        $navigationSchemaPathPatterns = $this->getConfig()->getNavigationSchemaPathPattern();
        $this->assertInternalType('array', $navigationSchemaPathPatterns);
        $this->assertCount(1, $navigationSchemaPathPatterns);
    }

    /**
     * @return void
     */
    public function testGetNavigationSchemaFileNamePatternShouldReturnString()
    {
        $this->assertInternalType('string', $this->getConfig()->getNavigationSchemaFileNamePattern());
    }

    /**
     * @return void
     */
    public function testGetRootNavigationSchemaShouldReturnString()
    {
        $this->assertInternalType('string', $this->getConfig()->getRootNavigationSchema());
    }

    /**
     * @return void
     */
    public function testGetCacheFileShouldReturnString()
    {
        $this->assertInternalType('string', $this->getConfig()->getCacheFile());
    }

    /**
     * @return void
     */
    public function testIsNavigationCacheEnabledShouldReturnBool()
    {
        $this->assertInternalType('bool', $this->getConfig()->isNavigationCacheEnabled());
    }

}
