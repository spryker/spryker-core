<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Kernel\Controller;

use Codeception\Test\Unit;
use Spryker\Yves\Kernel\Controller\RouteNameResolver;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Kernel
 * @group Controller
 * @group RouteNameResolverTest
 * Add your own group annotations below this line
 */
class RouteNameResolverTest extends Unit
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
