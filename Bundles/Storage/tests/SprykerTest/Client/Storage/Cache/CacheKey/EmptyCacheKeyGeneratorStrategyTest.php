<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Storage\Cache\CacheKey;

use Codeception\Test\Unit;
use Spryker\Client\Storage\Cache\CacheKey\EmptyCacheKeyGeneratorStrategy;
use Spryker\Client\Storage\StorageConfig;
use Spryker\Shared\Storage\StorageConstants;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group Storage
 * @group Cache
 * @group CacheKey
 * @group EmptyCacheKeyGeneratorStrategyTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Client\Storage\StorageClientTester $tester
 */
class EmptyCacheKeyGeneratorStrategyTest extends Unit
{
    /**
     * @var \Spryker\Client\Storage\Cache\CacheKey\EmptyCacheKeyGeneratorStrategy
     */
    protected $emptyCacheKeyGeneratorStrategy;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->emptyCacheKeyGeneratorStrategy = new EmptyCacheKeyGeneratorStrategy(
            new StorageConfig()
        );
    }

    /**
     * @return void
     */
    public function testGetsEmptyCacheKey(): void
    {
        $this->assertEquals('', $this->emptyCacheKeyGeneratorStrategy->generateCacheKey(
            Request::createFromGlobals()
        ));
    }

    /**
     * @dataProvider isApplicableProvider
     *
     * @param bool $isCacheEnabled
     * @param bool $expectedIsApplicable
     *
     * @return void
     */
    public function testIsApplicable(bool $isCacheEnabled, bool $expectedIsApplicable): void
    {
        $this->tester->setConfig(StorageConstants::STORAGE_CACHE_ENABLED, $isCacheEnabled);

        $this->assertEquals($expectedIsApplicable, $this->emptyCacheKeyGeneratorStrategy->isApplicable());
    }

    /**
     * @return bool[][]
     */
    public function isApplicableProvider(): array
    {
        return [
            'cache enabled' => [true, false],
            'cache disabled' => [false, true],
        ];
    }

    /**
     * @param bool $isCacheEnabled
     *
     * @return \Spryker\Client\Storage\StorageConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createConfigMock(bool $isCacheEnabled): StorageConfig
    {
        $configMock = $this->createMock(StorageConfig::class);
        $configMock->method('isStorageCachingEnabled')->willReturn($isCacheEnabled);

        return $configMock;
    }
}
