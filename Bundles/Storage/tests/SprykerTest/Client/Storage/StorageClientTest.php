<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Storage;

use Codeception\Test\Unit;
use Spryker\Client\Storage\StorageClient;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Storage\StorageConstants;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group Storage
 * @group StorageClientTest
 * Add your own group annotations below this line
 */
class StorageClientTest extends Unit
{
    const STORAGE_CACHE_STRATEGY = StorageConstants::STORAGE_CACHE_STRATEGY_REPLACE;

    /**
     * @var \Spryker\Client\Storage\StorageClient
     */
    protected $storageClientMock;

    /**
     * @return void
     */
    protected function _before()
    {
        $this->storageClientMock = $this
            ->getMockBuilder(StorageClient::class)
            ->setMethods(['updateCache'])
            ->getMock();
    }

    /**
     * @param string $uri
     * @param string $expectedCacheKey
     * @param array $allowedGetParameters
     * @param array $getParameters
     *
     * @return void
     */
    protected function testStorageCacheAllowedGetParameters(
        $uri,
        $expectedCacheKey,
        array $allowedGetParameters = [],
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
            self::STORAGE_CACHE_STRATEGY,
            $allowedGetParameters
        );
    }

    /**
     * @return string
     */
    protected function getStoreNameAndLocal()
    {
        return strtolower(Store::getInstance()->getStoreName()) . '.' .
            strtolower(Store::getInstance()->getCurrentLocale()) . '.';
    }

    /**
     * @return void
     */
    public function testGenerateCacheKeyWithNoGetParameter()
    {
        $uri = '/en/cameras-&-camcorders';
        $expectedCacheKey = $this->getStoreNameAndLocal() . 'storage./en/cameras-&-camcorders';

        $this->testStorageCacheAllowedGetParameters($uri, $expectedCacheKey);
    }

    /**
     * @return void
     */
    public function testGenerateCacheKeyWithOneAllowedGetParameterAndOneIsGiven()
    {
        $uri = '/en/cameras-&-camcorders?allowedParameter1=1';
        $expectedCacheKey = $this->getStoreNameAndLocal() . 'storage./en/cameras-&-camcorders?allowedParameter1=1';
        $allowedGetParameters = ['allowedParameter1'];
        $getParameters = ['allowedParameter1' => '1'];

        $this->testStorageCacheAllowedGetParameters(
            $uri,
            $expectedCacheKey,
            $allowedGetParameters,
            $getParameters
        );
    }

    /**
     * @return void
     */
    public function testGenerateCacheKeyWithTwoAllowedGetParameterAndOneIsGiven()
    {
        $uri = '/en/cameras-&-camcorders?allowedParameter1=1';
        $expectedCacheKey = $this->getStoreNameAndLocal() . 'storage./en/cameras-&-camcorders?allowedParameter1=1';
        $allowedGetParameters = ['allowedParameter1', 'allowedParameter2'];
        $getParameters = ['allowedParameter1' => '1'];

        $this->testStorageCacheAllowedGetParameters(
            $uri,
            $expectedCacheKey,
            $allowedGetParameters,
            $getParameters
        );
    }

    /**
     * @return void
     */
    public function testGenerateCacheKeyWithOneAllowedGetParameterAndTwoAreGiven()
    {
        $uri = '/en/cameras-&-camcorders?allowedParameter1=1&allowedParameter2=2';
        $expectedCacheKey = $this->getStoreNameAndLocal() . 'storage./en/cameras-&-camcorders?allowedParameter1=1';
        $allowedGetParameters = ['allowedParameter1'];
        $getParameters = ['allowedParameter1' => '1', 'allowedParameter2' => '2'];

        $this->testStorageCacheAllowedGetParameters(
            $uri,
            $expectedCacheKey,
            $allowedGetParameters,
            $getParameters
        );
    }

    /**
     * @return void
     */
    public function testGenerateCacheKeyWithTwoAllowedGetParameterAndTwoOrderedAreGiven()
    {
        $uri = '/en/cameras-&-camcorders?allowedParameter1=1&allowedParameter2=2';
        $expectedCacheKey = $this->getStoreNameAndLocal() . 'storage./en/cameras-&-camcorders?allowedParameter1=1&allowedParameter2=2';
        $allowedGetParameters = ['allowedParameter1', 'allowedParameter2'];
        $getParameters = ['allowedParameter1' => '1', 'allowedParameter2' => '2'];

        $this->testStorageCacheAllowedGetParameters(
            $uri,
            $expectedCacheKey,
            $allowedGetParameters,
            $getParameters
        );
    }

    /**
     * @return void
     */
    public function testGenerateCacheKeyWithTwoAllowedGetParameterAndTwoNotOrderedAreGiven()
    {
        $uri = '/en/cameras-&-camcorders?allowedParameter2=2&allowedParameter1=1';
        $expectedCacheKey = $this->getStoreNameAndLocal() . 'storage./en/cameras-&-camcorders?allowedParameter1=1&allowedParameter2=2';
        $allowedGetParameters = ['allowedParameter1', 'allowedParameter2'];
        $getParameters = ['allowedParameter1' => '1', 'allowedParameter2' => '2'];

        $this->testStorageCacheAllowedGetParameters(
            $uri,
            $expectedCacheKey,
            $allowedGetParameters,
            $getParameters
        );
    }
}
