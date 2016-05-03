<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\Kernel;

use Spryker\Yves\Kernel\BundleControllerAction;

/**
 * @group Spryker
 * @group Yves
 * @group Kernel
 * @group BundleControllerAction
 */
class BundleControllerActionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testGetBundleShouldReturnBundleName()
    {
        $bundleControllerLocator = new BundleControllerAction('foo', 'bar', 'baz');

        $this->assertSame('foo', $bundleControllerLocator->getBundle());
    }

    /**
     * @return void
     */
    public function testGetControllerShouldReturnControllerName()
    {
        $bundleControllerLocator = new BundleControllerAction('foo', 'bar', 'baz');

        $this->assertSame('bar', $bundleControllerLocator->getController());
    }

    /**
     * @return void
     */
    public function testGetActionShouldReturnActionName()
    {
        $bundleControllerLocator = new BundleControllerAction('foo', 'bar', 'baz');

        $this->assertSame('baz', $bundleControllerLocator->getAction());
    }

}
