<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\SalesPaymentMerchantPayoutCollectionTransfer;
use Generated\Shared\Transfer\SalesPaymentMerchantPayoutTransfer;
use Propel\Runtime\Collection\ObjectCollection;

class SalesPaymentMerchantPayoutMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayout> $salesPaymentMerchantPayoutEntities
     * @param \Generated\Shared\Transfer\SalesPaymentMerchantPayoutCollectionTransfer $salesPaymentMerchantPayoutCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SalesPaymentMerchantPayoutCollectionTransfer
     */
    public function mapSalesPaymentMerchantPayoutEntitiesToSalesPaymentMerchantPayoutCollectionTransfer(
        ObjectCollection $salesPaymentMerchantPayoutEntities,
        SalesPaymentMerchantPayoutCollectionTransfer $salesPaymentMerchantPayoutCollectionTransfer
    ): SalesPaymentMerchantPayoutCollectionTransfer {
        foreach ($salesPaymentMerchantPayoutEntities as $salesPaymentMerchantPayoutEntity) {
            $salesPaymentMerchantPayoutTransfer = new SalesPaymentMerchantPayoutTransfer();
            $salesPaymentMerchantPayoutTransfer->fromArray($salesPaymentMerchantPayoutEntity->toArray(), true);
            $salesPaymentMerchantPayoutCollectionTransfer->addSalesPaymentMerchantPayout($salesPaymentMerchantPayoutTransfer);
        }

        return $salesPaymentMerchantPayoutCollectionTransfer;
    }
}
