<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace YvesUnit\SprykerEngine\Yves\Kernel\Communication;

use SprykerEngine\Yves\Kernel\Communication\BundleControllerAction;

/**
 * @group SprykerEngine
 * @group Yves
 * @group Kernel
 * @group Communication
 * @group BundleControllerAction
 */
class BundleControllerActionTest extends \PHPUnit_Framework_TestCase
{

    public function testGetBundleShouldReturnBundleName()
    {
        $bundleControllerLocator = new BundleControllerAction('foo', 'bar', 'baz');

        $this->assertSame('foo', $bundleControllerLocator->getBundle());
    }

    public function testGetControllerShouldReturnControllerName()
    {
        $bundleControllerLocator = new BundleControllerAction('foo', 'bar', 'baz');

        $this->assertSame('bar', $bundleControllerLocator->getController());
    }

    public function testGetActionShouldReturnActionName()
    {
        $bundleControllerLocator = new BundleControllerAction('foo', 'bar', 'baz');

        $this->assertSame('baz', $bundleControllerLocator->getAction());
    }

}
