<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */


namespace Unit\Spryker\Yves\CheckoutStepEngine\Dependency\Plugin;

use Spryker\Yves\CheckoutStepEngine\Dependency\Plugin\CheckoutStepHandlerPluginCollection;
use Spryker\Yves\CheckoutStepEngine\Dependency\Plugin\CheckoutStepHandlerPluginInterface;

/**
 * @group Spryker
 * @group Yves
 * @group CheckoutStepEngine
 * @group CheckoutStepEngineStepHandlerPluginCollection
 */
class CheckoutStepHandlerPluginCollectionTest extends \PHPUnit_Framework_TestCase
{

    const TEST_PLUGIN_NAME = 'test';

    /**
     * @return void
     */
    public function testAdd()
    {
        $checkoutStepHandlerPluginCollection = new CheckoutStepHandlerPluginCollection();

        $this->assertInstanceOf(
            CheckoutStepHandlerPluginCollection::class,
            $checkoutStepHandlerPluginCollection->add($this->getCheckoutStepHandlerPlugin(), self::TEST_PLUGIN_NAME)
        );
    }

    /**
     * @return void
     */
    public function testHasReturnFalse()
    {
        $checkoutStepHandlerPluginCollection = new CheckoutStepHandlerPluginCollection();

        $this->assertFalse($checkoutStepHandlerPluginCollection->has(self::TEST_PLUGIN_NAME));
    }

    /**
     * @return void
     */
    public function testHasReturnTrue()
    {
        $checkoutStepHandlerPluginCollection = new CheckoutStepHandlerPluginCollection();
        $checkoutStepHandlerPluginCollection->add($this->getCheckoutStepHandlerPlugin(), self::TEST_PLUGIN_NAME);

        $this->assertTrue($checkoutStepHandlerPluginCollection->has(self::TEST_PLUGIN_NAME));
    }

    /**
     * @return void
     */
    public function testGet()
    {
        $checkoutStepHandlerPluginCollection = new CheckoutStepHandlerPluginCollection();
        $checkoutStepHandlerPluginCollection->add($this->getCheckoutStepHandlerPlugin(), self::TEST_PLUGIN_NAME);

        $this->assertInstanceOf(CheckoutStepHandlerPluginInterface::class, $checkoutStepHandlerPluginCollection->get(self::TEST_PLUGIN_NAME));
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Yves\CheckoutStepEngine\Dependency\Plugin\CheckoutStepHandlerPluginInterface
     */
    private function getCheckoutStepHandlerPlugin()
    {
        return $this->getMock(CheckoutStepHandlerPluginInterface::class);
    }

}
