<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Communication\Plugin\SalesPayment;

use Generated\Shared\Transfer\PaymentGiftCardCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesPaymentCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesPaymentExtension\Dependency\Plugin\SalesPaymentPreDeletePluginInterface;

/**
 * @method \Spryker\Zed\GiftCard\GiftCardConfig getConfig()
 * @method \Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface getFacade()
 * @method \Spryker\Zed\GiftCard\Communication\GiftCardCommunicationFactory getFactory()
 */
class PaymentGiftCardSalesPaymentPreDeletePlugin extends AbstractPlugin implements SalesPaymentPreDeletePluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `SalesPaymentTransfer.idSalesPayment` to be set for each sales payment in the provided collection.
     * - Removes found payment gift card entities.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesPaymentCollectionTransfer $salesPaymentCollectionTransfer
     *
     * @return void
     */
    public function preDelete(SalesPaymentCollectionTransfer $salesPaymentCollectionTransfer): void
    {
        $salesPaymentIds = [];
        foreach ($salesPaymentCollectionTransfer->getSalesPayments() as $salesPaymentTransfer) {
            $salesPaymentIds[] = $salesPaymentTransfer->getIdSalesPaymentOrFail();
        }

        $this->getFacade()->deletePaymentGiftCardCollection(
            (new PaymentGiftCardCollectionDeleteCriteriaTransfer())->setSalesPaymentIds($salesPaymentIds),
        );
    }
}
