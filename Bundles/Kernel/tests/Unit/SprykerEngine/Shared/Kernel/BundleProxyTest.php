<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Shared\Kernel;

use SprykerEngine\Shared\Kernel\BundleProxy;
use Unit\SprykerEngine\Shared\Kernel\Fixtures\LocatorLocator;
use Unit\SprykerEngine\Shared\Kernel\Fixtures\LocatorWithMatcher;
use Unit\SprykerEngine\Shared\Kernel\Fixtures\LocatorWithoutMatcher;

/**
 * @group Kernel
 * @group BundleProxy
 */
class BundleProxyTest extends \PHPUnit_Framework_TestCase
{

    public function testAddLocatorShouldAddLocatorCreateMatcherAndReturnBundleProxy()
    {
        $bundleProxy = new BundleProxy(LocatorLocator::getInstance());

        $this->assertInstanceOf(
            'SprykerEngine\Shared\Kernel\BundleProxy',
            $bundleProxy->addLocator(new LocatorWithMatcher('Foo'))
        );
    }

    public function testAddLocatorShouldThrowExceptionIfNoMatcherCanBeCreated()
    {
        $this->setExpectedException('\LogicException');

        $bundleProxy = new BundleProxy(LocatorLocator::getInstance());
        $bundleProxy->addLocator(new LocatorWithoutMatcher());
    }

    public function testSetBundleShouldReturnBundleProxy()
    {
        $bundleProxy = new BundleProxy(LocatorLocator::getInstance());

        $this->assertInstanceOf('SprykerEngine\Shared\Kernel\BundleProxy', $bundleProxy->setBundle('Foo'));
    }

    public function testCallShouldReturnLocatedClassIfMatcherMatchesToAGivenLocator()
    {
        $bundleProxy = new BundleProxy(LocatorLocator::getInstance());
        $locator = new LocatorWithMatcher('foo');
        $bundleProxy->addLocator($locator);

        $this->assertSame($locator, $bundleProxy->locatorTest());
    }

    public function testCallShouldThrowExceptionIfNoLocatorCanBeMatchedToCalledMethod()
    {
        $this->setExpectedException('\LogicException');

        $bundleProxy = new BundleProxy(LocatorLocator::getInstance());
        $bundleProxy->addLocator(new LocatorWithMatcher('Foo'));

        $bundleProxy->notMatchingTest();
    }

}
