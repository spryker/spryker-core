<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\QuoteApproval\Plugin\Permission;

use Codeception\Test\Unit;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface;
use Spryker\Shared\QuoteApproval\Plugin\Permission\ContextProvider\PermissionContextProviderInterface;
use Spryker\Shared\QuoteApproval\Plugin\Permission\PlaceOrderPermissionPlugin;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Shared
 * @group QuoteApproval
 * @group Plugin
 * @group Permission
 * @group PlaceOrderPermissionPluginTest
 * Add your own group annotations below this line
 */
class PlaceOrderPermissionPluginTest extends Unit
{
    protected const FIELD_MULTI_CURRENCY = 'store_multi_currency';
    protected const CURRENCY_CODE = 'EUR';
    protected const CENT_AMOUNT = 100;
    protected const CENT_SHIPMENT_COST = 20;
    protected const STORE_NAME = 'DE';

    /**
     * @return void
     */
    public function testCanWithValidDataReturnTrue(): void
    {
        $configuration[static::FIELD_MULTI_CURRENCY][static::STORE_NAME][static::CURRENCY_CODE] = static::CENT_AMOUNT;

        $placeOrderPermissionPlugin = $this->createPlaceOrderPermissionPlugin();
        $result = $placeOrderPermissionPlugin->can($configuration, $this->getContext());

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCanWithNullConfigurationCentAmountDataReturnTrue(): void
    {
        $configuration[static::FIELD_MULTI_CURRENCY][static::STORE_NAME][static::CURRENCY_CODE] = null;

        $placeOrderPermissionPlugin = $this->createPlaceOrderPermissionPlugin();
        $result = $placeOrderPermissionPlugin->can($configuration, $this->getContext());

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCanWithZeroConfigurationCentAmountDataReturnFalse(): void
    {
        $configuration[static::FIELD_MULTI_CURRENCY][static::STORE_NAME][static::CURRENCY_CODE] = 0;

        $placeOrderPermissionPlugin = $this->createPlaceOrderPermissionPlugin();
        $result = $placeOrderPermissionPlugin->can($configuration, $this->getContext());

        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testCanWithEmptyQuoteReturnFalse(): void
    {
        $configuration[static::FIELD_MULTI_CURRENCY][static::STORE_NAME][static::CURRENCY_CODE] = static::CENT_AMOUNT;

        $placeOrderPermissionPlugin = $this->createPlaceOrderPermissionPlugin();
        $result = $placeOrderPermissionPlugin->can($configuration, null);

        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testCanWithLessGrandTotalAmountReturnFalse(): void
    {
        $configuration[static::FIELD_MULTI_CURRENCY][static::STORE_NAME][static::CURRENCY_CODE] = static::CENT_AMOUNT - 1;

        $placeOrderPermissionPlugin = $this->createPlaceOrderPermissionPlugin();
        $result = $placeOrderPermissionPlugin->can($configuration, $this->getContext());

        $this->assertFalse($result);
    }

    /**
     * @return array
     */
    protected function getContext(): array
    {
        return [
            PermissionContextProviderInterface::CURRENCY_CODE => static::CURRENCY_CODE,
            PermissionContextProviderInterface::CENT_AMOUNT => static::CENT_AMOUNT,
            PermissionContextProviderInterface::STORE_NAME => static::STORE_NAME,
        ];
    }

    /**
     * @return \Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface
     */
    protected function createPlaceOrderPermissionPlugin(): ExecutablePermissionPluginInterface
    {
        return new PlaceOrderPermissionPlugin();
    }
}
