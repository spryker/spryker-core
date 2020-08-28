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

        $this->tester->getFactory()->createCacheBuilder()->build();

        $this->tester->assertCacheHasCoreClass();
    }

    /**
     * @return void
     */
    public function testBuildBuildsResolvableCacheForProjectClass(): void
    {
        $this->tester->arrangeProjectClassCacheBuilderTest();

        $this->tester->getFactory()->createCacheBuilder()->build();

        $this->tester->assertCacheHasProjectClass();
    }

    /**
     * @return void
     */
    public function testBuildBuildsResolvableCacheForStoreClass(): void
    {
        $this->tester->arrangeStoreClassCacheBuilderTest();

        $this->tester->getFactory()->createCacheBuilder()->build();

        $this->tester->assertCacheHasStoreClass();
    }
}
