<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\QuoteApproval\Communication\Plugin\Permission;

use Codeception\Test\Unit;
use Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface;
use Spryker\Shared\QuoteApproval\QuoteApprovalConfig;
use Spryker\Zed\QuoteApproval\Communication\Plugin\Permission\ApproveQuotePermissionPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group QuoteApproval
 * @group Communication
 * @group Plugin
 * @group Permission
 * @group ApproveQuotePermissionPluginTest
 * Add your own group annotations below this line
 */
class ApproveQuotePermissionPluginTest extends Unit
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

        $approveQuotePermissionPlugin = $this->createApproveQuotePermissionPlugin();
        $result = $approveQuotePermissionPlugin->can($configuration, $this->getContext());

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCanWithNullConfigurationCentAmountDataReturnTrue(): void
    {
        $configuration[static::FIELD_MULTI_CURRENCY][static::STORE_NAME][static::CURRENCY_CODE] = null;

        $approveQuotePermissionPlugin = $this->createApproveQuotePermissionPlugin();
        $result = $approveQuotePermissionPlugin->can($configuration, $this->getContext());

        $this->assertTrue($result);
    }

    /**
     * @return void
     */
    public function testCanWithZeroConfigurationCentAmountDataReturnFalse(): void
    {
        $configuration[static::FIELD_MULTI_CURRENCY][static::STORE_NAME][static::CURRENCY_CODE] = 0;

        $approveQuotePermissionPlugin = $this->createApproveQuotePermissionPlugin();
        $result = $approveQuotePermissionPlugin->can($configuration, $this->getContext());

        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testCanWithEmptyQuoteReturnFalse(): void
    {
        $configuration[static::FIELD_MULTI_CURRENCY][static::STORE_NAME][static::CURRENCY_CODE] = static::CENT_AMOUNT;

        $approveQuotePermissionPlugin = $this->createApproveQuotePermissionPlugin();
        $result = $approveQuotePermissionPlugin->can($configuration, null);

        $this->assertFalse($result);
    }

    /**
     * @return void
     */
    public function testCanWithLessGrandTotalAmountReturnFalse(): void
    {
        $configuration[static::FIELD_MULTI_CURRENCY][static::STORE_NAME][static::CURRENCY_CODE] = static::CENT_AMOUNT - 1;

        $approveQuotePermissionPlugin = $this->createApproveQuotePermissionPlugin();
        $result = $approveQuotePermissionPlugin->can($configuration, $this->getContext());

        $this->assertFalse($result);
    }

    /**
     * @return array
     */
    protected function getContext(): array
    {
        return [
            QuoteApprovalConfig::PERMISSION_CONTEXT_CENT_AMOUNT => static::CENT_AMOUNT,
            QuoteApprovalConfig::PERMISSION_CONTEXT_CURRENCY_CODE => static::CURRENCY_CODE,
            QuoteApprovalConfig::PERMISSION_CONTEXT_STORE_NAME => static::STORE_NAME,
        ];
    }

    /**
     * @return \Spryker\Shared\PermissionExtension\Dependency\Plugin\ExecutablePermissionPluginInterface
     */
    protected function createApproveQuotePermissionPlugin(): ExecutablePermissionPluginInterface
    {
        return new ApproveQuotePermissionPlugin();
    }
}
