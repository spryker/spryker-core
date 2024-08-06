<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SalesPaymentMerchant\Business\SalesPaymentMerchantBusinessFactory getFactory()
 * @method \Spryker\Zed\SalesPaymentMerchant\Persistence\SalesPaymentMerchantRepositoryInterface getRepository()
 * @method \Spryker\Zed\SalesPaymentMerchant\Persistence\SalesPaymentMerchantEntityManagerInterface getEntityManager()
 */
class SalesPaymentMerchantFacade extends AbstractFacade implements SalesPaymentMerchantFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $salesOrderItemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPayoutSupportedForPaymentMethodUsedForOrder(
        ItemTransfer $salesOrderItemTransfer,
        OrderTransfer $orderTransfer
    ): bool {
        return $this->getFactory()
            ->createPaymentMethodPayoutChecker()
            ->isPayoutSupportedForPaymentMethodUsedForOrder($salesOrderItemTransfer, $orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $salesOrderItemTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function payoutMerchants(array $salesOrderItemTransfers, OrderTransfer $orderTransfer): void
    {
        $this->getFactory()
            ->createMerchantPayout()
            ->payoutMerchants($salesOrderItemTransfers, $orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $salesOrderItemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPayoutReversalSupportedForPaymentMethodUsedForOrder(
        ItemTransfer $salesOrderItemTransfer,
        OrderTransfer $orderTransfer
    ): bool {
        return $this->getFactory()
            ->createPaymentMethodPayoutReverseChecker()
            ->isPayoutReversalSupportedForPaymentMethodUsedForOrder($salesOrderItemTransfer, $orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $salesOrderItemTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function reversePayoutMerchants(array $salesOrderItemTransfers, OrderTransfer $orderTransfer): void
    {
        $this->getFactory()
            ->createMerchantPaymentReverse()
            ->reversePayoutMerchants($salesOrderItemTransfers, $orderTransfer);
    }
}
