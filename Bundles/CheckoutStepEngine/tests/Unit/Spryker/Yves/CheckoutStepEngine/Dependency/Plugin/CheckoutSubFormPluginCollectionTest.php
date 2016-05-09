<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Yves\CheckoutStepEngine\Dependency\Plugin;

use Spryker\Yves\CheckoutStepEngine\Dependency\Plugin\CheckoutSubFormPluginCollection;
use Spryker\Yves\CheckoutStepEngine\Dependency\Plugin\CheckoutSubFormPluginInterface;

/**
 * @group Spryker
 * @group Yves
 * @group CheckoutStepEngine
 * @group CheckoutStepEngineSubFormPluginCollection
 */
class CheckoutSubFormPluginCollectionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return void
     */
    public function testAdd()
    {
        $checkoutSubFormPluginCollection = new CheckoutSubFormPluginCollection();

        $this->assertInstanceOf(
            CheckoutSubFormPluginCollection::class,
            $checkoutSubFormPluginCollection->add($this->getCheckoutSubFormPlugin())
        );
    }

    /**
     * @return void
     */
    public function testKey()
    {
        $checkoutSubFormPluginCollection = new CheckoutSubFormPluginCollection();

        $this->assertSame(0, $checkoutSubFormPluginCollection->key());
    }

    /**
     * @return void
     */
    public function testCollectionMustIterateable()
    {
        $checkoutSubFormPluginCollection = new CheckoutSubFormPluginCollection();
        $checkoutSubFormPluginCollection->add($this->getCheckoutSubFormPlugin());

        foreach ($checkoutSubFormPluginCollection as $checkoutSubFormPlugin) {
            $this->assertInstanceOf(CheckoutSubFormPluginInterface::class, $checkoutSubFormPlugin);
        }
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Yves\CheckoutStepEngine\Dependency\Plugin\CheckoutSubFormPluginInterface
     */
    private function getCheckoutSubFormPlugin()
    {
        return $this->getMock(CheckoutSubFormPluginInterface::class);
    }

}
