<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\Kernel\Controller;

use Spryker\Yves\Kernel\BundleControllerAction;
use Spryker\Yves\Kernel\Controller\BundleControllerActionRouteNameResolver;

/**
 * @group Unit
 * @group Spryker
 * @group Yves
 * @group Kernel
 * @group Controller
 * @group BundleControllerActionRouteNameResolverTest
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
