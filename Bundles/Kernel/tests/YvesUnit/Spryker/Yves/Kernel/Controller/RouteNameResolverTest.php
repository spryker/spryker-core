<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace YvesUnit\Spryker\Yves\Kernel;

use Spryker\Yves\Kernel\Controller\RouteNameResolver;

/**
 * @group Spryker
 * @group Yves
 * @group Kernel
 * @group Communication
 * @group RouteNameResolver
 */
class RouteNameResolverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testResolveShouldReturnResolvedRouteName()
    {
        $resolver = new RouteNameResolver('foo/bar/baz');

        $this->assertSame('foo/bar/baz', $resolver->resolve());
    }

}
