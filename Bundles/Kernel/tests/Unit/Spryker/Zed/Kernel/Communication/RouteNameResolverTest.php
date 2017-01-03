<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Kernel\Communication;

use PHPUnit_Framework_TestCase;
use Spryker\Zed\Kernel\Communication\Controller\RouteNameResolver;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Kernel
 * @group Communication
 * @group RouteNameResolverTest
 */
class RouteNameResolverTest extends PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testRouteNameResolverShouldReturnRouteNameExtractedFromRequest()
    {
        $request = new Request([], [], ['module' => 'foo', 'controller' => 'bar', 'action' => 'baz']);
        $routeNameResolver = new RouteNameResolver($request);

        $this->assertSame('Foo/Bar/baz', $routeNameResolver->resolve());
    }

}
