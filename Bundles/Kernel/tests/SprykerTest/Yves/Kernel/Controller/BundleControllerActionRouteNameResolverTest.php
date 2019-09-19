<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Kernel\Controller;

use Codeception\Test\Unit;
use Spryker\Yves\Kernel\BundleControllerAction;
use Spryker\Yves\Kernel\Controller\BundleControllerActionRouteNameResolver;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Yves
 * @group Kernel
 * @group Controller
 * @group BundleControllerActionRouteNameResolverTest
 * Add your own group annotations below this line
 */
class BundleControllerActionRouteNameResolverTest extends Unit
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
