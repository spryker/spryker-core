<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SalesPaymentMerchantPayoutReversalCollectionTransfer;
use Generated\Shared\Transfer\SalesPaymentMerchantPayoutReversalTransfer;
use Propel\Runtime\Collection\ObjectCollection;

class SalesPaymentMerchantPayoutReversalMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayoutReversal> $salesPaymentMerchantPayoutReversalEntities
     * @param \Generated\Shared\Transfer\SalesPaymentMerchantPayoutReversalCollectionTransfer $salesPaymentMerchantPayoutReversalCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SalesPaymentMerchantPayoutReversalCollectionTransfer
     */
    public function mapSalesPaymentMerchantPayoutReversalEntitiesToSalesPaymentMerchantPayoutReversalCollectionTransfer(
        ObjectCollection $salesPaymentMerchantPayoutReversalEntities,
        SalesPaymentMerchantPayoutReversalCollectionTransfer $salesPaymentMerchantPayoutReversalCollectionTransfer
    ): SalesPaymentMerchantPayoutReversalCollectionTransfer {
        foreach ($salesPaymentMerchantPayoutReversalEntities as $salesPaymentMerchantPayoutReversalEntity) {
            $salesPaymentMerchantPayoutReversalTransfer = new SalesPaymentMerchantPayoutReversalTransfer();
            $salesPaymentMerchantPayoutReversalTransfer->fromArray($salesPaymentMerchantPayoutReversalEntity->toArray(), true);
            $salesPaymentMerchantPayoutReversalCollectionTransfer->addSalesPaymentMerchantPayoutReversal($salesPaymentMerchantPayoutReversalTransfer);
        }

        return $salesPaymentMerchantPayoutReversalCollectionTransfer;
    }
}
