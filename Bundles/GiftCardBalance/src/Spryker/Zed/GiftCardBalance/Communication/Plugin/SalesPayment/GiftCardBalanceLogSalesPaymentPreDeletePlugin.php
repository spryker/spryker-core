<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardBalance\Communication\Plugin\SalesPayment;

use Generated\Shared\Transfer\GiftCardBalanceLogCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesPaymentCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesPaymentExtension\Dependency\Plugin\SalesPaymentPreDeletePluginInterface;

/**
 * @method \Spryker\Zed\GiftCardBalance\GiftCardBalanceConfig getConfig()
 * @method \Spryker\Zed\GiftCardBalance\Business\GiftCardBalanceFacadeInterface getFacade()
 * @method \Spryker\Zed\GiftCardBalance\Communication\GiftCardBalanceCommunicationFactory getFactory()
 */
class GiftCardBalanceLogSalesPaymentPreDeletePlugin extends AbstractPlugin implements SalesPaymentPreDeletePluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `SalesPaymentTransfer.fkSalesOrder` to be set for each sales payment in the provided collection.
     * - Removes gift card balance log entities.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesPaymentCollectionTransfer $salesPaymentCollectionTransfer
     *
     * @return void
     */
    public function preDelete(SalesPaymentCollectionTransfer $salesPaymentCollectionTransfer): void
    {
        $salesOrderIds = [];
        foreach ($salesPaymentCollectionTransfer->getSalesPayments() as $salesPaymentTransfer) {
            $salesOrderIds[] = $salesPaymentTransfer->getFkSalesOrderOrFail();
        }

        $this->getFacade()->deleteGiftCardBalanceLogCollection(
            (new GiftCardBalanceLogCollectionDeleteCriteriaTransfer())->setSalesOrderIds($salesOrderIds),
        );
    }
}
