<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\Kernel\Controller;

use PHPUnit_Framework_TestCase;
use Spryker\Yves\Kernel\Controller\RouteNameResolver;

/**
 * @group Unit
 * @group Spryker
 * @group Yves
 * @group Kernel
 * @group Controller
 * @group RouteNameResolverTest
 */
class RouteNameResolverTest extends PHPUnit_Framework_TestCase
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
