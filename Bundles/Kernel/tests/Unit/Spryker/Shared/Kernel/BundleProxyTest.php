<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Shared\Kernel;

use Spryker\Shared\Kernel\BundleProxy;
use Unit\Spryker\Shared\Kernel\Fixtures\LocatorWithMatcher;
use Unit\Spryker\Shared\Kernel\Fixtures\LocatorWithoutMatcher;

/**
 * @group Unit
 * @group Spryker
 * @group Shared
 * @group Kernel
 * @group BundleProxyTest
 */
class BundleProxyTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testAddLocatorShouldAddLocatorCreateMatcherAndReturnBundleProxy()
    {
        $bundleProxy = new BundleProxy();

        $this->assertInstanceOf(
            'Spryker\Shared\Kernel\BundleProxy',
            $bundleProxy->addLocator(new LocatorWithMatcher('Foo'))
        );
    }

    /**
     * @return void
     */
    public function testAddLocatorShouldThrowExceptionIfNoMatcherCanBeCreated()
    {
        $this->setExpectedException('\LogicException');

        $bundleProxy = new BundleProxy();
        $bundleProxy->addLocator(new LocatorWithoutMatcher());
    }

    /**
     * @return void
     */
    public function testSetBundleShouldReturnBundleProxy()
    {
        $bundleProxy = new BundleProxy();

        $this->assertInstanceOf('Spryker\Shared\Kernel\BundleProxy', $bundleProxy->setBundle('Foo'));
    }

    /**
     * @return void
     */
    public function testCallShouldReturnLocatedClassIfMatcherMatchesToAGivenLocator()
    {
        $bundleProxy = new BundleProxy();
        $locator = new LocatorWithMatcher('foo');
        $bundleProxy->addLocator($locator);

        $this->assertSame($locator, $bundleProxy->locatorTest());
    }

    /**
     * @return void
     */
    public function testCallShouldThrowExceptionIfNoLocatorCanBeMatchedToCalledMethod()
    {
        $this->setExpectedException('\LogicException');

        $bundleProxy = new BundleProxy();
        $bundleProxy->addLocator(new LocatorWithMatcher('Foo'));

        $bundleProxy->notMatchingTest();
    }

}
