<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Shared\Kernel;

use Spryker\Shared\Kernel\BundleProxy;
use Unit\Spryker\Shared\Kernel\Fixtures\LocatorLocator;
use Unit\Spryker\Shared\Kernel\Fixtures\LocatorWithMatcher;
use Unit\Spryker\Shared\Kernel\Fixtures\LocatorWithoutMatcher;

/**
 * @group Kernel
 * @group BundleProxy
 */
class BundleProxyTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testAddLocatorShouldAddLocatorCreateMatcherAndReturnBundleProxy()
    {
        $bundleProxy = new BundleProxy(LocatorLocator::getInstance());

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

        $bundleProxy = new BundleProxy(LocatorLocator::getInstance());
        $bundleProxy->addLocator(new LocatorWithoutMatcher());
    }

    /**
     * @return void
     */
    public function testSetBundleShouldReturnBundleProxy()
    {
        $bundleProxy = new BundleProxy(LocatorLocator::getInstance());

        $this->assertInstanceOf('Spryker\Shared\Kernel\BundleProxy', $bundleProxy->setBundle('Foo'));
    }

    /**
     * @return void
     */
    public function testCallShouldReturnLocatedClassIfMatcherMatchesToAGivenLocator()
    {
        $bundleProxy = new BundleProxy(LocatorLocator::getInstance());
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

        $bundleProxy = new BundleProxy(LocatorLocator::getInstance());
        $bundleProxy->addLocator(new LocatorWithMatcher('Foo'));

        $bundleProxy->notMatchingTest();
    }

}
