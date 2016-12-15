<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Navigation\Business\Model\Cache;

use PHPUnit_Framework_TestCase;
use Spryker\Service\UtilEncoding\UtilEncodingService;
use Spryker\Zed\Navigation\Business\Exception\NavigationCacheEmptyException;
use Spryker\Zed\Navigation\Business\Exception\NavigationCacheFileDoesNotExistException;
use Spryker\Zed\Navigation\Business\Model\Cache\NavigationCache;
use Spryker\Zed\Navigation\Dependency\Util\NavigationToUtilEncodingBridge;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Navigation
 * @group Business
 * @group Model
 * @group Cache
 * @group NavigationCacheTest
 */
class NavigationCacheTest extends PHPUnit_Framework_TestCase
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
        $navigationCache = new NavigationCache('', $isEnabled, $this->getUtilEncodingService());

        $this->assertFalse($navigationCache->isEnabled());
    }

    /**
     * @return void
     */
    public function testIsNavigationCacheEnabledMustReturnTrueIfEnabled()
    {
        $isEnabled = true;
        $navigationCache = new NavigationCache(__FILE__, $isEnabled, $this->getUtilEncodingService());

        $this->assertTrue($navigationCache->isEnabled());
    }

    /**
     * @return void
     */
    public function testSetMustSerializeGivenNavigationDataIntoFile()
    {
        $cacheFile = $this->getCacheFile();
        $isEnabled = true;
        $navigationCache = new NavigationCache($cacheFile, $isEnabled, $this->getUtilEncodingService());

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

        $navigationCache = new NavigationCache($cacheFile, $isEnabled, $this->getUtilEncodingService());
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
        $this->expectException(NavigationCacheFileDoesNotExistException::class);

        $isEnabled = true;
        $navigationCache = new NavigationCache('', $isEnabled, $this->getUtilEncodingService());
        $navigationCache->getNavigation();
    }

    /**
     * @return void
     */
    public function testGetMustThrowExceptionIfCacheEnabledCacheFileGivenButEmpty()
    {
        $this->expectException(NavigationCacheEmptyException::class);

        $cacheFile = $this->getCacheFile();
        $isEnabled = true;
        $navigationCache = new NavigationCache($cacheFile, $isEnabled, $this->getUtilEncodingService());
        $navigationCache->getNavigation();
    }

    /**
     * Checks, that JSON serialization is used in the cache.
     *
     * @return void
     */
    public function testCacheShouldNotUseSerialize()
    {
        $cacheFile = $this->getCacheFile();
        $isEnabled = true;

        $utilEncodingService = $this->getUtilEncodingService();

        $navigationCache = new NavigationCache($cacheFile, $isEnabled, $utilEncodingService);

        $navigationData = ['foo' => 'bar'];
        $navigationCache->setNavigation($navigationData);

        $rawData = file_get_contents($cacheFile);
        $this->assertEquals($navigationData, $utilEncodingService->decodeJson($rawData, true));
        $this->assertEquals($rawData, $utilEncodingService->encodeJson($navigationData));
    }

    /**
     * @return \Spryker\Zed\Navigation\Dependency\Util\NavigationToUtilEncodingBridge
     */
    protected function getUtilEncodingService()
    {
        $navigationToUtilEncodingBridge = new NavigationToUtilEncodingBridge(
            new UtilEncodingService()
        );

        return $navigationToUtilEncodingBridge;
    }

}
