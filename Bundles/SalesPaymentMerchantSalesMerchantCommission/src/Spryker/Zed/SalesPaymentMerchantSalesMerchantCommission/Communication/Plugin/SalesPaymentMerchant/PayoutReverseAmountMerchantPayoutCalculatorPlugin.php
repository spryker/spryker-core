<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Communication\Plugin\SalesPaymentMerchant;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesPaymentMerchantExtension\Communication\Dependency\Plugin\MerchantPayoutCalculatorPluginInterface;

/**
 * @method \Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\SalesPaymentMerchantSalesMerchantCommissionConfig getConfig()
 * @method \Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\SalesPaymentMerchantSalesMerchantCommissionFacadeInterface getFacade()
 */
class PayoutReverseAmountMerchantPayoutCalculatorPlugin extends AbstractPlugin implements MerchantPayoutCalculatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Calculates the payout reverse amount for the provided sales order item.
     * - Requires the `ItemTransfer.canceledAmount` property to be set.
     * - Requires the `ItemTransfer.taxAmountAfterCancellation` property to be set if tax deduction is enabled.
     * - Requires the `OrderTransfer.priceMode` property to be set.
     * - Requires the `OrderTransfer.store` property to be set if tax deduction is enabled.
     * - Expects the `ItemTransfer.merchantCommissionAmountFullAggregation` property to be set.
     * - Determines the price mode of the order (GROSS or NET) based on the configuration.
     * - Utilizes the configured base amount field for the calculation, which is `ItemTransfer::CANCELED_AMOUNT` or another configured field {@link \Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\SalesPaymentMerchantSalesMerchantCommissionConfig::getBaseAmountFieldForReversePayout()}.
     * - Applies tax deduction to the reverse payout amount if tax deduction is enabled for the given store and price mode in the configuration {@link \Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\SalesPaymentMerchantSalesMerchantCommissionConfig::isTaxDeductionEnabledForStoreAndPriceMode()}.
     * - Applies a commission refund based on the `ItemTransfer.merchantCommissionRefundedAmount` to the payout reverse amount.
     * - Returns the final calculated payout reverse amount.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    public function calculatePayoutAmount(ItemTransfer $itemTransfer, OrderTransfer $orderTransfer): int
    {
        return $this->getFacade()->calculatePayoutReverseAmount($itemTransfer, $orderTransfer);
    }
}
