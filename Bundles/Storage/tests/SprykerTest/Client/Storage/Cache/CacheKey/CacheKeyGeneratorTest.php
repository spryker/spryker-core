<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\Storage\Cache\CacheKey;

use Codeception\Test\Unit;
use Spryker\Client\Storage\Cache\CacheKey\CacheKeyGenerator;
use Spryker\Client\Storage\Cache\CacheKey\CacheKeyGeneratorStrategyInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group Storage
 * @group Cache
 * @group CacheKey
 * @group CacheKeyGeneratorTest
 * Add your own group annotations below this line
 */
class CacheKeyGeneratorTest extends Unit
{
    /**
     * @expectedException \Spryker\Client\Storage\Exception\InvalidCacheKeyGeneratorStrategyException
     *
     * @return void
     */
    public function testThrowsExceptionWhenNoStrategyApplied(): void
    {
        $cacheKeyGenerator = new CacheKeyGenerator([]);

        $cacheKeyGenerator->generateCacheKey(
            Request::createFromGlobals()
        );
    }

    /**
     * @dataProvider generatesCacheKeyProvider
     *
     * @param bool $isFirstStrategyAllowed
     * @param bool $isSecondStrategyAllowed
     * @param string $firstCacheKey
     * @param string $secondCacheKey
     * @param string $expectedCacheKey
     *
     * @return void
     */
    public function testGeneratesCacheKey(bool $isFirstStrategyAllowed, bool $isSecondStrategyAllowed, string $firstCacheKey, string $secondCacheKey, string $expectedCacheKey): void
    {
        $dummyCacheKeyGeneratorStrategy = $this->createDummyCacheKeyGeneratorStrategy();
        $dummyCacheKeyGeneratorStrategy->method('isApplicable')->willReturn($isFirstStrategyAllowed);
        $dummyCacheKeyGeneratorStrategy->method('generateCacheKey')->willReturn($firstCacheKey);

        $anotherDummyCacheKeyGeneratorStrategy = $this->createDummyCacheKeyGeneratorStrategy();
        $anotherDummyCacheKeyGeneratorStrategy->method('isApplicable')->willReturn($isSecondStrategyAllowed);
        $anotherDummyCacheKeyGeneratorStrategy->method('generateCacheKey')->willReturn($secondCacheKey);

        $cacheKeyGenerator = new CacheKeyGenerator([
            $dummyCacheKeyGeneratorStrategy,
            $anotherDummyCacheKeyGeneratorStrategy,
        ]);

        $this->assertEquals($expectedCacheKey, $cacheKeyGenerator->generateCacheKey(Request::createFromGlobals()));
    }

    /**
     * @return array
     */
    public function generatesCacheKeyProvider(): array
    {
        $cacheKey = 'cache key';
        $anotherCacheKey = 'another cache key';

        return [
            'first strategy allowed' => [true, false, $cacheKey, $anotherCacheKey, $cacheKey],
            'second strategy allowed' => [false, true, $cacheKey, $anotherCacheKey, $anotherCacheKey],
            'both strategies allowed' => [true, true, $cacheKey, $anotherCacheKey, $cacheKey],
        ];
    }

    /**
     * @return \Spryker\Client\Storage\Cache\CacheKey\CacheKeyGeneratorStrategyInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function createDummyCacheKeyGeneratorStrategy(): CacheKeyGeneratorStrategyInterface
    {
        return $this->createMock(CacheKeyGeneratorStrategyInterface::class);
    }
}
