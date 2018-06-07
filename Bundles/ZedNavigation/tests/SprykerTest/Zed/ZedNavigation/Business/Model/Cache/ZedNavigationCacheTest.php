<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ZedNavigation\Business\Model\Cache;

use Codeception\Test\Unit;
use Spryker\Service\UtilEncoding\UtilEncodingService;
use Spryker\Zed\ZedNavigation\Business\Exception\ZedNavigationCacheEmptyException;
use Spryker\Zed\ZedNavigation\Business\Exception\ZedNavigationCacheFileDoesNotExistException;
use Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCache;
use Spryker\Zed\ZedNavigation\Dependency\Util\ZedNavigationToUtilEncodingBridge;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ZedNavigation
 * @group Business
 * @group Model
 * @group Cache
 * @group ZedNavigationCacheTest
 * Add your own group annotations below this line
 */
class ZedNavigationCacheTest extends Unit
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

    //@TODO to decorator
//    /**
//     * @return void
//     */
//    public function testIsNavigationCacheEnabledMustReturnFalseIfItIsNotEnabled()
//    {
//        $isEnabled = false;
//        $navigationCache = new ZedNavigationCache('', $isEnabled, $this->getUtilEncodingService());
//
//        $this->assertFalse($navigationCache->isEnabled());
//    }
//
//    /**
//     * @return void
//     */
//    public function testIsNavigationCacheEnabledMustReturnTrueIfEnabled()
//    {
//        $isEnabled = true;
//        $navigationCache = new ZedNavigationCache(__FILE__, $isEnabled, $this->getUtilEncodingService());
//
//        $this->assertTrue($navigationCache->isEnabled());
//    }
//
//    /**
//     * @return void
//     */
//    public function testSetMustSerializeGivenNavigationDataIntoFile()
//    {
//        $cacheFile = $this->getCacheFile();
//        $isEnabled = true;
//        $navigationCache = new ZedNavigationCache($cacheFile, $isEnabled, $this->getUtilEncodingService());
//        $navigationData = ['foo' => 'bar'];
//        $navigationCache->setNavigation($navigationData);
//
//        $this->assertTrue($navigationCache->isEnabled());
//    }

    /**
     * @return void
     */
    public function testGetMustReturnUnSerializedNavigationDataFromFile(): void
    {
        $cacheFile = $this->getCacheFile();
        $isEnabled = true;

        $navigationCache = new ZedNavigationCache($cacheFile, $isEnabled, $this->getUtilEncodingService());
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
        $this->expectException(ZedNavigationCacheFileDoesNotExistException::class);

        $isEnabled = true;
        $navigationCache = new ZedNavigationCache('', $isEnabled, $this->getUtilEncodingService());
        $navigationCache->getNavigation();
    }

    /**
     * @return void
     */
    public function testGetMustThrowExceptionIfCacheEnabledCacheFileGivenButEmpty()
    {
        $this->expectException(ZedNavigationCacheEmptyException::class);

        $cacheFile = $this->getCacheFile();
        $isEnabled = true;
        $navigationCache = new ZedNavigationCache($cacheFile, $isEnabled, $this->getUtilEncodingService());
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

        $navigationCache = new ZedNavigationCache($cacheFile, $isEnabled, $utilEncodingService);

        $navigationData = ['foo' => 'bar'];
        $navigationCache->setNavigation($navigationData);

        $rawData = file_get_contents($cacheFile);
        $this->assertEquals($navigationData, $utilEncodingService->decodeJson($rawData, true));
        $this->assertEquals($rawData, $utilEncodingService->encodeJson($navigationData));
    }

    /**
     * @return \Spryker\Zed\ZedNavigation\Dependency\Util\ZedNavigationToUtilEncodingBridge
     */
    protected function getUtilEncodingService()
    {
        $navigationToUtilEncodingBridge = new ZedNavigationToUtilEncodingBridge(
            new UtilEncodingService()
        );

        return $navigationToUtilEncodingBridge;
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
}
