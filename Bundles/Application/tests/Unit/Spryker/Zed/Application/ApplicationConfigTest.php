<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Application;

use Generated\Zed\Ide\AutoCompletion;
use Spryker\Shared\Config;
use Spryker\Shared\Kernel\AbstractLocatorLocator;
use Spryker\Zed\Kernel\Locator;
use Spryker\Zed\Application\ApplicationConfig;

class ApplicationConfigTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return ApplicationConfig
     */
    private function getConfig()
    {
        return new ApplicationConfig(Config::getInstance(), $this->getLocator());
    }

    /**
     * @return AbstractLocatorLocator|static|AutoCompletion
     */
    private function getLocator()
    {
        return Locator::getInstance();
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
