<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Storage;

use Codeception\Test\Unit;
use Spryker\Client\Storage\StorageClient;
use Spryker\Client\Storage\StorageFactory;
use Spryker\Client\StorageExtension\Dependency\Plugin\StoragePluginInterface;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Storage\StorageConstants;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group Storage
 * @group StorageClientTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Client\Storage\StorageClientTester $tester
 */
class StorageClientTest extends Unit
{
    public const STORAGE_CACHE_STRATEGY = StorageConstants::STORAGE_CACHE_STRATEGY_REPLACE;

    /**
     * @var \Spryker\Client\Storage\StorageClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $storageClientMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->setupStorageClientMock();
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
        $storageClient = $this->createStorageClient();
        $storageClient::$service = null;
    }

    /**
     * @param string $uri
     * @param string $expectedCacheKey
     * @param array $getParameters
     *
     * @return void
     */
    protected function testStorageCacheAllowedGetParameters(
        $uri,
        $expectedCacheKey,
        array $getParameters = []
    ) {
        $request = new Request();
        $request->server->set('SERVER_NAME', 'localhost');
        $request->server->set('REQUEST_URI', $uri);
        $request->query = new ParameterBag($getParameters);

        $this->storageClientMock->expects($this->once())
            ->method('updateCache')
            ->with(
                $this->equalTo(self::STORAGE_CACHE_STRATEGY),
                $this->equalTo($expectedCacheKey)
            );

        $this->storageClientMock->persistCacheForRequest(
            $request,
            static::STORAGE_CACHE_STRATEGY
        );
    }

    /**
     * @return string
     */
    protected function getStoreAndLocale(): string
    {
        return strtolower(Store::getInstance()->getStoreName()) . '.' .
            strtolower(Store::getInstance()->getCurrentLocale()) . '.';
    }

    /**
     * @return void
     */
    public function testGenerateCacheKeyWithNoGetParameter(): void
    {
        $this->markTestSkipped(
            'This test will be updated in the next major.'
        );

        $uri = '/en/cameras-&-camcorders';
        $expectedCacheKey = $this->getStoreAndLocale() . 'storage:/en/cameras-&-camcorders';

        $this->testStorageCacheAllowedGetParameters($uri, $expectedCacheKey);
    }

    /**
     * @return void
     */
    public function testGenerateCacheKeyWithOneAllowedGetParameterAndOneIsGiven(): void
    {
        $this->markTestSkipped(
            'This test will be updated in the next major.'
        );

        $uri = '/en/cameras-&-camcorders?allowedParameter1=1';
        $expectedCacheKey = $this->getStoreAndLocale() . 'storage:/en/cameras-&-camcorders?allowedParameter1=1';
        $getParameters = ['allowedParameter1' => '1'];

        $this->testStorageCacheAllowedGetParameters(
            $uri,
            $expectedCacheKey,
            $getParameters
        );
    }

    /**
     * @return void
     */
    public function testGenerateCacheKeyWithTwoAllowedGetParameterAndOneIsGiven(): void
    {
        $this->markTestSkipped(
            'This test will be updated in the next major.'
        );

        $uri = '/en/cameras-&-camcorders?allowedParameter1=1';
        $expectedCacheKey = $this->getStoreAndLocale() . 'storage:/en/cameras-&-camcorders?allowedParameter1=1';
        $getParameters = ['allowedParameter1' => '1'];

        $this->testStorageCacheAllowedGetParameters(
            $uri,
            $expectedCacheKey,
            $getParameters
        );
    }

    /**
     * @return void
     */
    public function testGenerateCacheKeyWithOneAllowedGetParameterAndTwoAreGiven(): void
    {
        $this->markTestSkipped(
            'This test will be updated in the next major.'
        );

        $uri = '/en/cameras-&-camcorders?allowedParameter1=1&allowedParameter2=2';
        $expectedCacheKey = $this->getStoreAndLocale() . 'storage:/en/cameras-&-camcorders?allowedParameter1=1';
        $getParameters = ['allowedParameter1' => '1', 'allowedParameter2' => '2'];

        $this->testStorageCacheAllowedGetParameters(
            $uri,
            $expectedCacheKey,
            $getParameters
        );
    }

    /**
     * @return void
     */
    public function testGenerateCacheKeyWithTwoAllowedGetParameterAndTwoOrderedAreGiven()
    {
        $this->markTestSkipped(
            'This test will be updated in the next major.'
        );

        $uri = '/en/cameras-&-camcorders?allowedParameter1=1&allowedParameter2=2';
        $expectedCacheKey = $this->getStoreAndLocale() . 'storage:/en/cameras-&-camcorders?allowedParameter1=1&allowedParameter2=2';
        $getParameters = ['allowedParameter1' => '1', 'allowedParameter2' => '2'];

        $this->testStorageCacheAllowedGetParameters(
            $uri,
            $expectedCacheKey,
            $getParameters
        );
    }

    /**
     * @return void
     */
    public function testGenerateCacheKeyWithTwoAllowedGetParameterAndTwoNotOrderedAreGiven(): void
    {
        $this->markTestSkipped(
            'This test will be updated in the next major.'
        );

        $uri = '/en/cameras-&-camcorders?allowedParameter2=2&allowedParameter1=1';
        $expectedCacheKey = $this->getStoreAndLocale() . 'storage:/en/cameras-&-camcorders?allowedParameter1=1&allowedParameter2=2';
        $getParameters = ['allowedParameter1' => '1', 'allowedParameter2' => '2'];

        $this->testStorageCacheAllowedGetParameters(
            $uri,
            $expectedCacheKey,
            $getParameters
        );
    }

    /**
     * @expectedException \Spryker\Client\Storage\Exception\InvalidStorageScanPluginInterfaceException
     *
     * @return void
     */
    public function testInvalidStorageScanPluginInterfaceExceptionThrown(): void
    {
        /** @var \Spryker\Client\StorageExtension\Dependency\Plugin\StoragePluginInterface|\PHPUnit\Framework\MockObject\MockObject $storagePluginMock */
        $storagePluginMock = $this->getMockBuilder(StoragePluginInterface::class)->getMock();

        $storageClient = $this->createStorageClient();
        $storageClient::$service = $storagePluginMock;

        $storageClient->scanKeys('*', 100);
    }

    /**
     * @return void
     */
    public function testCacheIsDisabled(): void
    {
        $this->tester->setConfig(StorageConstants::STORAGE_CACHE_ENABLED, false);

        $this->storageClientMock->expects($this->never())
            ->method('updateCache');

        $this->storageClientMock->persistCacheForRequest(
            Request::createFromGlobals(),
            static::STORAGE_CACHE_STRATEGY
        );
    }

    /**
     * @return void
     */
    public function testCacheIsEnabled(): void
    {
        $this->tester->setConfig(StorageConstants::STORAGE_CACHE_ENABLED, true);
        $this->storageClientMock->setCachedKeys([]);
        $request = new Request();
        $request->server->set('SERVER_NAME', 'localhost');
        $request->server->set('REQUEST_URI', '/uri');

        $this->storageClientMock->expects($this->once())
            ->method('updateCache');

        $this->storageClientMock->persistCacheForRequest(
            $request,
            static::STORAGE_CACHE_STRATEGY
        );
    }

    /**
     * @return \Spryker\Client\Storage\StorageClient
     */
    protected function createStorageClient(): StorageClient
    {
        return new StorageClient();
    }

    /**
     * @return void
     */
    protected function setupStorageClientMock(): void
    {
        $this->storageClientMock = $this->getMockBuilder(StorageClient::class)
            ->setMethods(['getFactory', 'updateCache'])
            ->getMock();
        $this->storageClientMock->method('getFactory')->willReturn(new StorageFactory());
    }
}
