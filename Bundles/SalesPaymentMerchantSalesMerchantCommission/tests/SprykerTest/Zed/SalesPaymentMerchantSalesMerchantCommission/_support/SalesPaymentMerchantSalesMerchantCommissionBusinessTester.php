<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesPaymentMerchantSalesMerchantCommission;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\OrderBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\SalesPaymentMerchantSalesMerchantCommissionConfig;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 *
 * @method \Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\SalesPaymentMerchantSalesMerchantCommissionFacadeInterface getFacade(?string $moduleName = null)
 */
class SalesPaymentMerchantSalesMerchantCommissionBusinessTester extends Actor
{
    use _generated\SalesPaymentMerchantSalesMerchantCommissionBusinessTesterActions;

    /**
     * @var string
     */
    protected const STORE_NAME = 'DE';

    /**
     * @param bool $isGrossPriceMode
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function createItemTransfer(bool $isGrossPriceMode = false): ItemTransfer
    {
        return (new ItemBuilder([
            ItemTransfer::SUM_PRICE_TO_PAY_AGGREGATION => 10000,
            ItemTransfer::SUM_NET_PRICE => $isGrossPriceMode ? null : 10000,
            ItemTransfer::SUM_TAX_AMOUNT => 2000,
            ItemTransfer::MERCHANT_COMMISSION_AMOUNT_FULL_AGGREGATION => 500,
        ]))->build();
    }

    /**
     * @param bool $isGrossMode
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createOrderTransfer(bool $isGrossMode = false): OrderTransfer
    {
        return (new OrderBuilder([
            OrderTransfer::STORE => static::STORE_NAME,
            OrderTransfer::PRICE_MODE => $isGrossMode ? SalesPaymentMerchantSalesMerchantCommissionConfig::PRICE_MODE_GROSS : SalesPaymentMerchantSalesMerchantCommissionConfig::PRICE_MODE_NET,
        ]))->build();
    }
}
