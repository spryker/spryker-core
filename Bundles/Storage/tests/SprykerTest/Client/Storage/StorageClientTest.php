<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Storage;

use Codeception\Test\Unit;
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
            self::STORAGE_CACHE_STRATEGY
        );
    }

    /**
     * @return string
     */
    protected function getStoreAndLocale()
    {
        return strtolower(Store::getInstance()->getStoreName()) . '.' .
            strtolower(Store::getInstance()->getCurrentLocale()) . '.';
    }

    /**
     * @return void
     */
    public function testGenerateCacheKeyWithNoGetParameter()
    {
        $this->markTestSkipped(
            'This test will be updated in the next major.'
        );

        $uri = '/en/cameras-&-camcorders';
        $expectedCacheKey = $this->getStoreAndLocale() . 'storage./en/cameras-&-camcorders';

        $this->testStorageCacheAllowedGetParameters($uri, $expectedCacheKey);
    }

    /**
     * @return void
     */
    public function testGenerateCacheKeyWithOneAllowedGetParameterAndOneIsGiven()
    {
        $this->markTestSkipped(
            'This test will be updated in the next major.'
        );

        $uri = '/en/cameras-&-camcorders?allowedParameter1=1';
        $expectedCacheKey = $this->getStoreAndLocale() . 'storage./en/cameras-&-camcorders?allowedParameter1=1';
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
    public function testGenerateCacheKeyWithTwoAllowedGetParameterAndOneIsGiven()
    {
        $this->markTestSkipped(
            'This test will be updated in the next major.'
        );

        $uri = '/en/cameras-&-camcorders?allowedParameter1=1';
        $expectedCacheKey = $this->getStoreAndLocale() . 'storage./en/cameras-&-camcorders?allowedParameter1=1';
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
    public function testGenerateCacheKeyWithOneAllowedGetParameterAndTwoAreGiven()
    {
        $this->markTestSkipped(
            'This test will be updated in the next major.'
        );

        $uri = '/en/cameras-&-camcorders?allowedParameter1=1&allowedParameter2=2';
        $expectedCacheKey = $this->getStoreAndLocale() . 'storage./en/cameras-&-camcorders?allowedParameter1=1';
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
        $expectedCacheKey = $this->getStoreAndLocale() . 'storage./en/cameras-&-camcorders?allowedParameter1=1&allowedParameter2=2';
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
    public function testGenerateCacheKeyWithTwoAllowedGetParameterAndTwoNotOrderedAreGiven()
    {
        $this->markTestSkipped(
            'This test will be updated in the next major.'
        );

        $uri = '/en/cameras-&-camcorders?allowedParameter2=2&allowedParameter1=1';
        $expectedCacheKey = $this->getStoreAndLocale() . 'storage./en/cameras-&-camcorders?allowedParameter1=1&allowedParameter2=2';
        $getParameters = ['allowedParameter1' => '1', 'allowedParameter2' => '2'];

        $this->testStorageCacheAllowedGetParameters(
            $uri,
            $expectedCacheKey,
            $getParameters
        );
    }
}
