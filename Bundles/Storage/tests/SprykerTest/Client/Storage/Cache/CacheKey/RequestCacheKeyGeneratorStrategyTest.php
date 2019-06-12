<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Storage\Cache\CacheKey;

use Codeception\Test\Unit;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Storage\Dependency\Client\StorageToLocaleClientBridge;
use Spryker\Client\Storage\Dependency\Client\StorageToStoreClientBridge;
use Spryker\Client\Storage\StorageConfig;
use Spryker\Client\Storage\StorageDependencyProvider;
use Spryker\Client\Storage\StorageFactory;
use Spryker\Shared\Kernel\Store;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group Storage
 * @group Cache
 * @group CacheKey
 * @group RequestCacheKeyGeneratorStrategyTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Client\Storage\StorageClientTester $tester
 */
class RequestCacheKeyGeneratorStrategyTest extends Unit
{
    protected const KEY_NAME_PREFIX = 'storage';
    protected const KEY_NAME_SEPARATOR = ':';

    /**
     * @var \Spryker\Client\Storage\Cache\CacheKey\CacheKeyGeneratorStrategyInterface
     */
    protected $requestCacheKeyGeneratorStrategy;

    /**
     * @var string[]
     */
    protected $allowedQueryStringParameters = [
        'allowedParameter1',
        'allowedParameter2',
    ];

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->setupDependencies();

        $this->requestCacheKeyGeneratorStrategy = $this->createStorageFactory()->createRequestCacheKeyGeneratorStrategy();
    }

    /**
     * @dataProvider generatesEmptyKeyWhenServerNameOrUriAreEmptyProvider
     *
     * @param string $serverName
     * @param string $requestUri
     * @param bool $expectedResult
     *
     * @return void
     */
    public function testGeneratesEmptyKeyWhenServerNameOrUriAreEmpty(string $serverName, string $requestUri, bool $expectedResult): void
    {
        // Arrange
        $request = new Request();
        $request->server->set('SERVER_NAME', $serverName);
        $request->server->set('REQUEST_URI', $requestUri);

        // Act
        $cacheKey = $this->requestCacheKeyGeneratorStrategy->generateCacheKey($request);

        // Assert
        $this->assertEquals($expectedResult, empty($cacheKey));
    }

    /**
     * @return array
     */
    public function generatesEmptyKeyWhenServerNameOrUriAreEmptyProvider(): array
    {
        return [
            'server name empty' => ['', 'uri', true],
            'request uri empty' => ['server name', '', true],
            'server name and request uri empty' => ['', '', true],
            'server name and request uri not empty' => ['server name', 'uri', false],
        ];
    }

    /**
     * @dataProvider generatesCacheKeyProvider
     *
     * @param string $expectedCacheKey
     * @param string $requestUri
     * @param string[] $queryStringParameters
     *
     * @return void
     */
    public function testGeneratesCacheKey(string $expectedCacheKey, string $requestUri, array $queryStringParameters = []): void
    {
        // Arrange
        $request = new Request();
        $request->server->set('SERVER_NAME', 'localhost');
        $request->server->set('REQUEST_URI', $requestUri);
        $request->query = new ParameterBag($queryStringParameters);

        // Act
        $actualCacheKey = $this->requestCacheKeyGeneratorStrategy->generateCacheKey($request);

        // Assert
        $this->assertEquals($expectedCacheKey, $actualCacheKey);
    }

    /**
     * @return array
     */
    public function generatesCacheKeyProvider(): array
    {
        return [
            'no query string parameters' => [
                $this->buildExpectedCacheKey('/en/request-uri'),
                '/en/request-uri',
            ],
            'one allowed query string parameter' => [
                $this->buildExpectedCacheKey('/en/another-request-uri?allowedParameter1=1'),
                '/en/another-request-uri',
                [
                    'allowedParameter1' => '1',
                ],
            ],
            'both allowed query string parameters' => [
                $this->buildExpectedCacheKey('/en/yet-another-request-uri?allowedParameter1=1&allowedParameter2=2'),
                '/en/yet-another-request-uri',
                [
                    'allowedParameter1' => '1',
                    'allowedParameter2' => '2',
                ],
            ],
            'one allowed and one disallowed query string parameter' => [
                $this->buildExpectedCacheKey('/en/cameras-&-camcorders?allowedParameter2=2'),
                '/en/cameras-&-camcorders',
                [
                    'disallowedParameter1' => '1',
                    'allowedParameter2' => '2',
                ],
            ],
            'only disallowed query string parameters' => [
                $this->buildExpectedCacheKey('/en/computers'),
                '/en/computers',
                [
                    'disallowedParameter1' => '1',
                    'disallowedParameter2' => '2',
                ],
            ],
        ];
    }

    /**
     * @param string $expectedRequestUriFragment
     *
     * @return string
     */
    protected function buildExpectedCacheKey(string $expectedRequestUriFragment): string
    {
        return implode(static::KEY_NAME_SEPARATOR, [$this->buildCacheKeyPrefix(), $expectedRequestUriFragment]);
    }

    /**
     * @return string
     */
    protected function buildCacheKeyPrefix(): string
    {
        return implode(static::KEY_NAME_SEPARATOR, [
            Store::getInstance()->getStoreName(),
            Store::getInstance()->getCurrentLocale(),
            static::KEY_NAME_PREFIX,
        ]);
    }

    /**
     * @return \Spryker\Client\Storage\StorageFactory
     */
    protected function createStorageFactory(): StorageFactory
    {
        $storageFactory = new StorageFactory();
        $storageFactory->setConfig(
            $this->createStorageConfigMock()
        );

        return $storageFactory;
    }

    /**
     * @return \Spryker\Client\Storage\StorageConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createStorageConfigMock(): StorageConfig
    {
        $storageConfigMock = $this->createMock(StorageConfig::class);
        $storageConfigMock->method('getAllowedGetParametersList')->willReturn($this->allowedQueryStringParameters);

        return $storageConfigMock;
    }

    /**
     * @return void
     */
    protected function setupDependencies(): void
    {
        $this->tester->setDependency(StorageDependencyProvider::CLIENT_LOCALE, function (Container $container) {
            return new StorageToLocaleClientBridge(
                $container->getLocator()->locale()->client()
            );
        });

        $this->tester->setDependency(StorageDependencyProvider::CLIENT_STORE, function (Container $container) {
            return new StorageToStoreClientBridge(
                $container->getLocator()->store()->client()
            );
        });
    }
}
