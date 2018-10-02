<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\CheckoutPermissionConnector\Plugin\PermissionExtension;

use Codeception\Test\Unit;
use Spryker\Shared\CheckoutPermissionConnector\Plugin\Permission\PlaceOrderWithAmountUpToPermissionPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Client
 * @group CheckoutPermissionConnector
 * @group Plugin
 * @group PermissionExtension
 * @group PlaceOrderWithAmountUpToPermissionPluginTest
 * Add your own group annotations below this line
 */
class PlaceOrderWithAmountUpToPermissionPluginTest extends Unit
{
    protected const FIELD_CENT_AMOUNT = 'cent_amount';

    /**
     * @return void
     */
    public function testCanReturnsFalseWhenAmountIsNotProvided(): void
    {
        $configuration = [
            static::FIELD_CENT_AMOUNT => 10,
        ];
        $centAmount = null;

        $placeOrderWithAmountUpToPermissionPlugin = new PlaceOrderWithAmountUpToPermissionPlugin();
        $placeOrderWithAmountUpToPermissionPluginResult = $placeOrderWithAmountUpToPermissionPlugin
            ->can($configuration, $centAmount);

        $this->assertFalse($placeOrderWithAmountUpToPermissionPluginResult);
    }

    /**
     * @return void
     */
    public function testCanReturnsTrueWhenConfigurationIsMissing(): void
    {
        $configuration = [];
        $centAmount = 100;

        $placeOrderWithAmountUpToPermissionPlugin = new PlaceOrderWithAmountUpToPermissionPlugin();
        $placeOrderWithAmountUpToPermissionPluginResult = $placeOrderWithAmountUpToPermissionPlugin
            ->can($configuration, $centAmount);

        $this->assertTrue($placeOrderWithAmountUpToPermissionPluginResult);
    }

    /**
     * @return void
     */
    public function testCanReturnsFalseWhenConfiguredAmountIsExceeded(): void
    {
        $configuration = [
            static::FIELD_CENT_AMOUNT => 10,
        ];
        $centAmount = 100;

        $placeOrderWithAmountUpToPermissionPlugin = new PlaceOrderWithAmountUpToPermissionPlugin();
        $placeOrderWithAmountUpToPermissionPluginResult = $placeOrderWithAmountUpToPermissionPlugin
            ->can($configuration, $centAmount);

        $this->assertFalse($placeOrderWithAmountUpToPermissionPluginResult);
    }

    /**
     * @return void
     */
    public function testCanReturnsTrueWhenAmountIsBelowConfiguredValue(): void
    {
        $configuration = [
            static::FIELD_CENT_AMOUNT => 100,
        ];
        $centAmount = 10;

        $placeOrderWithAmountUpToPermissionPlugin = new PlaceOrderWithAmountUpToPermissionPlugin();
        $placeOrderWithAmountUpToPermissionPluginResult = $placeOrderWithAmountUpToPermissionPlugin
            ->can($configuration, $centAmount);

        $this->assertTrue($placeOrderWithAmountUpToPermissionPluginResult);
    }
}
