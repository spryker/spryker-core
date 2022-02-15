<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Kernel\Business\ClassResolver;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Kernel
 * @group Business
 * @group ClassResolver
 * @group CacheBuilderTest
 * Add your own group annotations below this line
 */
class CacheBuilderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Kernel\KernelZedTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testBuildBuildsResolvableCacheForCoreClass(): void
    {
        $this->tester->arrangeCoreClassCacheBuilderTest();

        $this->tester->getBusinessFactory()->createCacheBuilder()->build();

        $this->assertCacheHasExpectedValue(
            $this->tester->getCacheKey(),
            $this->tester->getDefaultModuleNamePostfixValue(),
            $this->tester->getAutoloadableCoreClassName(),
        );
    }

    /**
     * @return void
     */
    public function testBuildBuildsResolvableCacheForProjectClass(): void
    {
        $this->tester->arrangeProjectClassCacheBuilderTest();

        $this->tester->getBusinessFactory()->createCacheBuilder()->build();

        $this->assertCacheHasExpectedValue(
            $this->tester->getCacheKey(),
            $this->tester->getDefaultModuleNamePostfixValue(),
            $this->tester->getAutoloadableProjectClassName(),
        );
    }

    /**
     * @return void
     */
    public function testBuildBuildsResolvableCacheForCodeBucketClass(): void
    {
        $this->tester->arrangeCodeBucketClassCacheBuilderTest();

        $this->tester->getBusinessFactory()->createCacheBuilder()->build();

        $this->assertCacheHasExpectedValue(
            $this->tester->getCacheKey(),
            $this->tester->getDefaultModuleNamePostfixValue(),
            $this->tester->getAutoloadableProjectClassName(),
        );

        foreach ($this->tester->getCodeBuckets() as $codeBucket) {
            $this->assertCacheHasExpectedValue(
                $this->tester->getCacheKey(),
                $codeBucket,
                $this->tester->getAutoloadableCodeBucketClassName($codeBucket),
            );
        }
    }

    /**
     * @param string $cacheKey
     * @param string $cacheFileNamePostfix
     * @param string $expectedCacheValue
     *
     * @return void
     */
    protected function assertCacheHasExpectedValue(string $cacheKey, string $cacheFileNamePostfix, string $expectedCacheValue): void
    {
        $this->assertFileExists($this->tester->getPathToCacheFile($cacheFileNamePostfix), 'Cache file does not exists.');

        $cachedData = $this->tester->getCacheData($cacheFileNamePostfix);

        $this->assertgreaterthan(0, $cachedData, 'At least one cache entry expected but cache is empty.');
        $this->assertArrayHasKey($cacheKey, $cachedData, sprintf('Cache key "%s" not found. Found cache keys: %s', $cacheKey, implode(', ', array_keys($cachedData))));

        $currentCacheValue = $cachedData[$cacheKey];

        $this->assertSame(
            $expectedCacheValue,
            $currentCacheValue,
            sprintf('Expected "%s" but found "%s" for cache key "%s" given.', $expectedCacheValue, $currentCacheValue, $cacheKey),
        );
    }
}
