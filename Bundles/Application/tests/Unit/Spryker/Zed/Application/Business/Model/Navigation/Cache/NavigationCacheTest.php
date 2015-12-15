<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Application\Business\Model\Navigation\Cache;

use Spryker\Zed\Application\Business\Model\Navigation\Cache\NavigationCache;

/**
 * @group Spryker
 * @group Zed
 * @group Application
 * @group Business
 * @group NavigationCache
 */
class NavigationCacheTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        $cacheFile = $this->getCacheFile();
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
    }

    /**
     * @return string
     */
    private function getCacheFile()
    {
        return __DIR__ . 'navigation.cache';
    }

    /**
     * @return void
     */
    public function testIsNavigationCacheEnabledMustReturnFalseIfItIsNotEnabled()
    {
        $isEnabled = false;
        $navigationCache = new NavigationCache('', $isEnabled);

        $this->assertFalse($navigationCache->isEnabled());
    }

    /**
     * @return void
     */
    public function testIsNavigationCacheEnabledMustReturnFalseIfItIsEnabledButCacheFileDoesNotExist()
    {
        $isEnabled = true;
        $navigationCache = new NavigationCache('', $isEnabled);

        $this->assertFalse($navigationCache->isEnabled());
    }

    /**
     * @return void
     */
    public function testIsNavigationCacheEnabledMustReturnFalseIfItNotEnabledButCacheFileExist()
    {
        $isEnabled = false;
        $navigationCache = new NavigationCache(__FILE__, $isEnabled);

        $this->assertFalse($navigationCache->isEnabled());
    }

    /**
     * @return void
     */
    public function testIsNavigationCacheEnabledMustReturnTrueIfEnabledAndCacheFileExist()
    {
        $isEnabled = true;
        $navigationCache = new NavigationCache(__FILE__, $isEnabled);

        $this->assertTrue($navigationCache->isEnabled());
    }

    /**
     * @return void
     */
    public function testSetMustSerializeGivenNavigationDataIntoFile()
    {
        $cacheFile = $this->getCacheFile();
        $isEnabled = true;

        $navigationCache = new NavigationCache($cacheFile, $isEnabled);
        $this->assertFalse($navigationCache->isEnabled());

        $navigationData = ['foo' => 'bar'];
        $navigationCache->setNavigation($navigationData);

        $this->assertTrue($navigationCache->isEnabled());
    }

    /**
     * @return void
     */
    public function testGetMustReturnUnSerializedNavigationDataFromFile()
    {
        $cacheFile = $this->getCacheFile();
        $isEnabled = true;

        $navigationCache = new NavigationCache($cacheFile, $isEnabled);
        $navigationData = ['foo' => 'bar'];
        $navigationCache->setNavigation($navigationData);

        $cachedNavigationData = $navigationCache->getNavigation();
        $this->assertSame($navigationData, $cachedNavigationData);
    }

}
