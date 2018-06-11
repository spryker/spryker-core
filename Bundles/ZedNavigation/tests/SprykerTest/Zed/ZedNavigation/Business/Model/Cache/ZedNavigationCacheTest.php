<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ZedNavigation\Business\Model\Cache;

use Spryker\Service\UtilEncoding\UtilEncodingService;
use Spryker\Zed\ZedNavigation\Business\Exception\ZedNavigationCacheEmptyException;
use Spryker\Zed\ZedNavigation\Business\Exception\ZedNavigationCacheFileDoesNotExistException;
use Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCache;
use Spryker\Zed\ZedNavigation\Dependency\Util\ZedNavigationToUtilEncodingBridge;
use SprykerTest\Zed\ZedNavigation\Business\ZedNavigationBusinessTester;

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
class ZedNavigationCacheTest extends ZedNavigationBusinessTester
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
     * @return void
     */
    public function testIsNavigationCacheHasContentMustReturnFalseOnNotExistsFile()
    {
        //prepare
        $navigationCache = $this->getZedNavigationCache('');

        //assert
        $this->assertFalse($navigationCache->hasContent());
    }

    /**
     * @return void
     */
    public function testIsNavigationCacheHasContentMustReturnTrue()
    {
        //prepare
        $navigationCache = $this->getZedNavigationCache(__FILE__);

        //assert
        $this->assertTrue($navigationCache->hasContent());
    }

    /**
     * @return void
     */
    public function testSetMustSerializeGivenNavigationDataIntoFile()
    {
        //prepare
        $navigationCache = $this->getZedNavigationCache();
        $navigationData = ['foo' => 'bar'];

        //act
        $navigationCache->setNavigation($navigationData);

        //asser
        $this->assertTrue($navigationCache->isEnabled());
    }

    /**
     * @return void
     */
    public function testGetMustReturnUnSerializedNavigationDataFromFile(): void
    {
        //prepare
        $navigationCache = $this->getZedNavigationCache();
        $navigationData = ['foo' => 'bar'];

        //act
        $navigationCache->setNavigation($navigationData);
        $cachedNavigationData = $navigationCache->getNavigation();

        //assert
        $this->assertSame($navigationData, $cachedNavigationData);
    }

    /**
     * @return void
     */
    public function testGetMustThrowExceptionIfCacheEnabledButCacheFileDoesNotExists(): void
    {
        //prepare
        $navigationCache = $this->getZedNavigationCache('');

        //assert
        $this->expectException(ZedNavigationCacheFileDoesNotExistException::class);

        //act
        $navigationCache->getNavigation();
    }

    /**
     * @return void
     */
    public function testGetMustThrowExceptionIfCacheEnabledCacheFileGivenButEmpty(): void
    {
        //prepare
        $navigationCache = $this->getZedNavigationCache();

        //assert
        $this->expectException(ZedNavigationCacheEmptyException::class);

        //act
        $navigationCache->getNavigation();
    }

    /**
     * Checks, that JSON serialization is used in the cache.
     *
     * @return void
     */
    public function testCacheShouldNotUseSerialize(): void
    {
        //prepare
        $cacheFile = $this->getCacheFile();
        $isEnabled = true;
        $utilEncodingService = $this->getUtilEncodingService();
        $navigationCache = new ZedNavigationCache($cacheFile, $isEnabled, $utilEncodingService);
        $navigationData = ['foo' => 'bar'];

        //act
        $navigationCache->setNavigation($navigationData);

        //assert
        $rawData = file_get_contents($cacheFile);
        $this->assertEquals($navigationData, $utilEncodingService->decodeJson($rawData, true));
        $this->assertEquals($rawData, $utilEncodingService->encodeJson($navigationData));
    }

    /**
     * @param null|string $cacheFile
     *
     * @return \Spryker\Zed\ZedNavigation\Business\Model\Cache\ZedNavigationCache
     */
    protected function getZedNavigationCache(?string $cacheFile = null): ZedNavigationCache
    {
        if ($cacheFile === null) {
            $cacheFile = $this->getCacheFile();
        }

        $utilEncodingService = $this->getUtilEncodingService();

        return new ZedNavigationCache($cacheFile, true, $utilEncodingService);
    }

    /**
     * @return \Spryker\Zed\ZedNavigation\Dependency\Util\ZedNavigationToUtilEncodingBridge
     */
    protected function getUtilEncodingService(): ZedNavigationToUtilEncodingBridge
    {
        $navigationToUtilEncodingBridge = new ZedNavigationToUtilEncodingBridge(
            new UtilEncodingService()
        );

        return $navigationToUtilEncodingBridge;
    }

    /**
     * @return string
     */
    protected function getCacheFile(): string
    {
        $pathToFile = __DIR__ . DIRECTORY_SEPARATOR . 'navigation.cache';

        if (!file_exists($pathToFile)) {
            touch($pathToFile);
        }

        return $pathToFile;
    }
}
