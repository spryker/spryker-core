<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Application\Business\Model\Navigation\Cache;

use Spryker\Zed\Application\Business\Exception\NavigationCacheFileDoesNotExistException;
use Spryker\Zed\Application\Business\Exception\NavigationCacheEmptyException;
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
        $pathToFile = __DIR__ . DIRECTORY_SEPARATOR . 'navigation.cache';

        if (!file_exists($pathToFile)) {
            touch($pathToFile);
        }

        return $pathToFile;
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
    public function testIsNavigationCacheEnabledMustReturnTrueIfEnabled()
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

    /**
     * @return void
     */
    public function testGetMustThrowExceptionIfCacheEnabledButCacheFileDoesNotExists()
    {
        $this->setExpectedException(NavigationCacheFileDoesNotExistException::class);

        $isEnabled = true;
        $navigationCache = new NavigationCache('', $isEnabled);
        $navigationCache->getNavigation();
    }

    /**
     * @return void
     */
    public function testGetMustThrowExceptionIfCacheEnabledCacheFileGivenButEmpty()
    {
        $this->setExpectedException(NavigationCacheEmptyException::class);

        $cacheFile = $this->getCacheFile();
        $isEnabled = true;
        $navigationCache = new NavigationCache($cacheFile, $isEnabled);
        $navigationCache->getNavigation();
    }

}
