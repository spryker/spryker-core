<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Payment\Dependency\Plugin\Checkout;

use Codeception\Test\Unit;
use Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPluginCollection;
use Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPluginCollectionInterface;
use Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPluginInterface;
use Spryker\Zed\Payment\Exception\CheckoutPluginNotFoundException;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Payment
 * @group Dependency
 * @group Plugin
 * @group Checkout
 * @group CheckoutPluginCollectionTest
 * Add your own group annotations below this line
 */
class CheckoutPluginCollectionTest extends Unit
{
    public const PROVIDER = 'provider';
    public const PLUGIN_TYPE = 'plugin type';

    /**
     * @return void
     */
    public function testAddShouldReturnInstance()
    {
        $checkoutPluginCollection = new CheckoutPluginCollection();
        $pluginMock = $this->getPluginMock();
        $result = $checkoutPluginCollection->add($pluginMock, self::PROVIDER, self::PLUGIN_TYPE);

        $this->assertInstanceOf(CheckoutPluginCollectionInterface::class, $result);
    }

    /**
     * @return void
     */
    public function testHasShouldReturnFalse()
    {
        $checkoutPluginCollection = new CheckoutPluginCollection();

        $this->assertFalse($checkoutPluginCollection->has(self::PROVIDER, self::PLUGIN_TYPE));
    }

    /**
     * @return void
     */
    public function testHasShouldReturnTrue()
    {
        $checkoutPluginCollection = new CheckoutPluginCollection();
        $pluginMock = $this->getPluginMock();
        $checkoutPluginCollection->add($pluginMock, self::PROVIDER, self::PLUGIN_TYPE);

        $this->assertTrue($checkoutPluginCollection->has(self::PROVIDER, self::PLUGIN_TYPE));
    }

    /**
     * @return void
     */
    public function testGetShouldReturnPluginForGivenProviderAndPluginType()
    {
        $checkoutPluginCollection = new CheckoutPluginCollection();
        $pluginMock = $this->getPluginMock();
        $checkoutPluginCollection->add($pluginMock, self::PROVIDER, self::PLUGIN_TYPE);
        $result = $checkoutPluginCollection->get(self::PROVIDER, self::PLUGIN_TYPE);

        $this->assertSame($pluginMock, $result);
    }

    /**
     * @return void
     */
    public function testGetShouldThrowExceptionIfProviderNotFound()
    {
        $checkoutPluginCollection = new CheckoutPluginCollection();
        $pluginMock = $this->getPluginMock();
        $checkoutPluginCollection->add($pluginMock, self::PROVIDER, self::PLUGIN_TYPE);
        $this->expectException(CheckoutPluginNotFoundException::class);
        $this->expectExceptionMessage('Could not find any plugin for "unknown" provider. You need to add the needed plugins within your DependencyInjector.');

        $checkoutPluginCollection->get('unknown', self::PLUGIN_TYPE);
    }

    /**
     * @return void
     */
    public function testGetShouldThrowExceptionIfPluginTypeNotFound()
    {
        $checkoutPluginCollection = new CheckoutPluginCollection();
        $pluginMock = $this->getPluginMock();
        $checkoutPluginCollection->add($pluginMock, self::PROVIDER, self::PLUGIN_TYPE);
        $this->expectException(CheckoutPluginNotFoundException::class);
        $this->expectExceptionMessage('Could not find "unknown" plugin type for "provider" provider. You need to add the needed plugins within your DependencyInjector.');

        $checkoutPluginCollection->get(self::PROVIDER, 'unknown');
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Payment\Dependency\Plugin\Checkout\CheckoutPluginInterface
     */
    private function getPluginMock()
    {
        return $this->getMockBuilder(CheckoutPluginInterface::class)->getMock();
    }
}
