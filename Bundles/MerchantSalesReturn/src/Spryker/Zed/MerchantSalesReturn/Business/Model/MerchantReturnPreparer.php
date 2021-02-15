<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Business\Model;

use Generated\Shared\Transfer\ReturnTransfer;

class MerchantReturnPreparer implements MerchantReturnPreparerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer
     */
    public function prepareReturn(ReturnTransfer $returnTransfer): ReturnTransfer
    {
        $returnItemTransfers = $returnTransfer->getReturnItems();

        if ($returnItemTransfers->count() < 1) {
            throw new \Exception();
        }

        /** @var \Generated\Shared\Transfer\ReturnItemTransfer $firstReturnItem */
        $firstReturnItem = $returnItemTransfers->offsetGet(0);

        $merchantReference = $firstReturnItem
            ->getOrderItemOrFail()
            ->getMerchantReference();

        $returnTransfer->setMerchantReference($merchantReference);

        return $returnTransfer;
    }
}
