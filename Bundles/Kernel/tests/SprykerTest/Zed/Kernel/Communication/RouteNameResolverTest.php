<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Kernel\Communication;

use Codeception\Test\Unit;
use Spryker\Zed\Kernel\Communication\Controller\RouteNameResolver;
use Symfony\Component\HttpFoundation\Request;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Kernel
 * @group Communication
 * @group RouteNameResolverTest
 * Add your own group annotations below this line
 */
class RouteNameResolverTest extends Unit
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
