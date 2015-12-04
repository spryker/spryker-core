<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace YvesUnit\SprykerEngine\Yves\Kernel;

use SprykerEngine\Yves\Kernel\BundleControllerAction;
use SprykerEngine\Yves\Kernel\Controller\BundleControllerActionRouteNameResolver;

/**
 * @group SprykerEngine
 * @group Yves
 * @group Kernel
 * @group BundleControllerActionRouteNameResolver
 */
class BundleControllerActionRouteNameResolverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testResolveShouldReturnResolvedRouteName()
    {
        $bundleControllerAction = new BundleControllerAction('Foo', 'Bar', 'baz');
        $resolver = new BundleControllerActionRouteNameResolver($bundleControllerAction);

        $this->assertSame('foo/bar/baz', $resolver->resolve());
    }

}
