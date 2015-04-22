<?php

namespace Unit\SprykerEngine\Yves\Kernel\Communication;

use SprykerEngine\Yves\Kernel\Communication\Controller\RouteNameResolver;

/**
 * @group Kernel
 * @group Communication
 * @group Controller
 * @group RouteNameResolver
 */
class RouteNameResolverTest extends \PHPUnit_Framework_TestCase
{

    public function testResolveShouldReturnResolvedRouteName()
    {
        $resolver = new RouteNameResolver('foo/bar/baz');

        $this->assertSame('foo/bar/baz', $resolver->resolve());
    }
}
