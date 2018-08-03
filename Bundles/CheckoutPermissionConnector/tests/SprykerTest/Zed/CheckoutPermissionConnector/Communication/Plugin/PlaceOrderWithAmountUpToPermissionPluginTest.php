<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CheckoutPermissionConnector\Communication\Plugin;

use Codeception\Test\Unit;
use Spryker\Zed\CheckoutPermissionConnector\Communication\Plugin\PlaceOrderWithAmountUpToPermissionPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CheckoutPermissionConnector
 * @group Communication
 * @group Plugin
 * @group PlaceOrderWithAmountUpToPermissionPluginTest
 * Add your own group annotations below this line
 */
class PlaceOrderWithAmountUpToPermissionPluginTest extends Unit
{
    protected const FIELD_CENT_AMOUNT = 'cent_amount';

    /**
     * @return void
     */
    public function testCanWithNullAmount(): void
    {
        $configuration[static::FIELD_CENT_AMOUNT] = 10;
        $centAmount = null;

        $placeOrderWithAmountUpToPermissionPlugin = new PlaceOrderWithAmountUpToPermissionPlugin();
        $placeOrderWithAmountUpToPermissionPluginResult = $placeOrderWithAmountUpToPermissionPlugin
            ->can($configuration, $centAmount);

        $this->assertFalse($placeOrderWithAmountUpToPermissionPluginResult);
    }

    /**
     * @return void
     */
    public function testCanWithEmptyConfiguration(): void
    {
        $configuration = [];
        $centAmount = 100;

        $placeOrderWithAmountUpToPermissionPlugin = new PlaceOrderWithAmountUpToPermissionPlugin();
        $placeOrderWithAmountUpToPermissionPluginResult = $placeOrderWithAmountUpToPermissionPlugin
            ->can($configuration, $centAmount);

        $this->assertFalse($placeOrderWithAmountUpToPermissionPluginResult);
    }

    /**
     * @return void
     */
    public function testCanWithHigherCentAmount(): void
    {
        $configuration[static::FIELD_CENT_AMOUNT] = 10;
        $centAmount = 100;

        $placeOrderWithAmountUpToPermissionPlugin = new PlaceOrderWithAmountUpToPermissionPlugin();
        $placeOrderWithAmountUpToPermissionPluginResult = $placeOrderWithAmountUpToPermissionPlugin
            ->can($configuration, $centAmount);

        $this->assertFalse($placeOrderWithAmountUpToPermissionPluginResult);
    }

    /**
     * @return void
     */
    public function testCanWithLowerCentAmount(): void
    {
        $configuration[static::FIELD_CENT_AMOUNT] = 100;
        $centAmount = 10;

        $placeOrderWithAmountUpToPermissionPlugin = new PlaceOrderWithAmountUpToPermissionPlugin();
        $placeOrderWithAmountUpToPermissionPluginResult = $placeOrderWithAmountUpToPermissionPlugin
            ->can($configuration, $centAmount);

        $this->assertTrue($placeOrderWithAmountUpToPermissionPluginResult);
    }
}
