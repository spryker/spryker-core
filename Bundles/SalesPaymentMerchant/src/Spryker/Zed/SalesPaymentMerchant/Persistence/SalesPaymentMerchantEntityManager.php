<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Persistence;

use Generated\Shared\Transfer\TransferResponseTransfer;
use Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayout;
use Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayoutReversal;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\SalesPaymentMerchant\Persistence\SalesPaymentMerchantPersistenceFactory getFactory()
 */
class SalesPaymentMerchantEntityManager extends AbstractEntityManager implements SalesPaymentMerchantEntityManagerInterface
{
 /**
  * @param \Generated\Shared\Transfer\TransferResponseTransfer $transferResponseTransfer
  *
  * @return void
  */
    public function saveSalesPaymentMerchantPayout(TransferResponseTransfer $transferResponseTransfer): void
    {
        $salesPaymentMerchantPayoutEntity = new SpySalesPaymentMerchantPayout();
        $salesPaymentMerchantPayoutEntity->fromArray($transferResponseTransfer->toArray());
        $salesPaymentMerchantPayoutEntity->save();
    }

    /**
     * @param \Generated\Shared\Transfer\TransferResponseTransfer $transferResponseTransfer
     *
     * @return void
     */
    public function saveSalesPaymentMerchantPayoutReversal(
        TransferResponseTransfer $transferResponseTransfer
    ): void {
        $salesPaymentMerchantPayoutReversalEntity = new SpySalesPaymentMerchantPayoutReversal();
        $salesPaymentMerchantPayoutReversalEntity->fromArray($transferResponseTransfer->toArray());
        $salesPaymentMerchantPayoutReversalEntity->save();
    }
}
