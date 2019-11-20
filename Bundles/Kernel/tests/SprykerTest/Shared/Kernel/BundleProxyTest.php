<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Kernel;

use Codeception\Test\Unit;
use LogicException;
use Spryker\Shared\Kernel\BundleProxy;
use SprykerTest\Shared\Kernel\Fixtures\LocatorWithMatcher;
use SprykerTest\Shared\Kernel\Fixtures\LocatorWithoutMatcher;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Shared
 * @group Kernel
 * @group BundleProxyTest
 * Add your own group annotations below this line
 */
class BundleProxyTest extends Unit
{
    /**
     * @return void
     */
    public function testAddLocatorShouldAddLocatorCreateMatcherAndReturnBundleProxy(): void
    {
        $bundleProxy = new BundleProxy();

        $this->assertInstanceOf(BundleProxy::class, $bundleProxy->addLocator(new LocatorWithMatcher()));
    }

    /**
     * @return void
     */
    public function testAddLocatorShouldThrowExceptionIfNoMatcherCanBeCreated(): void
    {
        $this->expectException(LogicException::class);

        $bundleProxy = new BundleProxy();
        $bundleProxy->addLocator(new LocatorWithoutMatcher());
    }

    /**
     * @return void
     */
    public function testSetBundleShouldReturnBundleProxy(): void
    {
        $bundleProxy = new BundleProxy();

        $this->assertInstanceOf(BundleProxy::class, $bundleProxy->setBundle('Foo'));
    }

    /**
     * @return void
     */
    public function testCallShouldReturnLocatedClassIfMatcherMatchesToAGivenLocator(): void
    {
        $bundleProxy = new BundleProxy();
        $locator = new LocatorWithMatcher();
        $bundleProxy->addLocator($locator);

        $this->assertSame($locator, $bundleProxy->locatorTest());
    }

    /**
     * @return void
     */
    public function testCallShouldThrowExceptionIfNoLocatorCanBeMatchedToCalledMethod(): void
    {
        $this->expectException(LogicException::class);

        $bundleProxy = new BundleProxy();
        $bundleProxy->addLocator(new LocatorWithMatcher());

        $bundleProxy->notMatchingTest();
    }
}
