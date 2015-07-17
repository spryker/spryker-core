<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Application\Business\Model\Navigation\Cache;

use SprykerFeature\Zed\Application\Business\Model\Navigation\Cache\NavigationCache;

/**
 * @group SprykerFeature
 * @group Zed
 * @group Application
 * @group Business
 * @group NavigationCache
 */
class NavigationCacheTest extends \PHPUnit_Framework_TestCase
{

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

    public function testIsNavigationCacheEnabledMustReturnFalseIfItIsNotEnabled()
    {
        $isEnabled = false;
        $navigationCache = new NavigationCache('', $isEnabled);

        $this->assertFalse($navigationCache->isEnabled());
    }

    public function testIsNavigationCacheEnabledMustReturnFalseIfItIsEnabledButCacheFileDoesNotExist()
    {
        $isEnabled = true;
        $navigationCache = new NavigationCache('', $isEnabled);

        $this->assertFalse($navigationCache->isEnabled());
    }

    public function testIsNavigationCacheEnabledMustReturnFalseIfItNotEnabledButCacheFileExist()
    {
        $isEnabled = false;
        $navigationCache = new NavigationCache(__FILE__, $isEnabled);

        $this->assertFalse($navigationCache->isEnabled());
    }

    public function testIsNavigationCacheEnabledMustReturnTrueIfEnabledAndCacheFileExist()
    {
        $isEnabled = true;
        $navigationCache = new NavigationCache(__FILE__, $isEnabled);

        $this->assertTrue($navigationCache->isEnabled());
    }

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
