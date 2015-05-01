<?php

namespace YvesUnit\SprykerEngine\Yves\Kernel\Communication;

use SprykerEngine\Yves\Kernel\Communication\BundleControllerAction;
use SprykerEngine\Yves\Kernel\Communication\Controller\BundleControllerActionRouteNameResolver;

/**
 * @group Kernel
 * @group Communication
 * @group Controller
 * @group RouteNameResolver
 */
class BundleControllerActionRouteNameResolverTest extends \PHPUnit_Framework_TestCase
{

    public function testResolveShouldReturnResolvedRouteName()
    {
        $bundleControllerAction = new BundleControllerAction('Foo', 'Bar', 'baz');
        $resolver = new BundleControllerActionRouteNameResolver($bundleControllerAction);

        $this->assertSame('foo/bar/baz', $resolver->resolve());
    }
}
