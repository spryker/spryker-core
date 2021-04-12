<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Business\Expander;

use Generated\Shared\Transfer\ReturnCollectionTransfer;

class MerchantReturnCollectionExpander implements MerchantReturnCollectionExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReturnCollectionTransfer $returnCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnCollectionTransfer
     */
    public function expand(ReturnCollectionTransfer $returnCollectionTransfer): ReturnCollectionTransfer
    {
        foreach ($returnCollectionTransfer->getReturns() as $returnTransfer) {
            $returnItemFirst = $returnTransfer->getReturnItems()->offsetGet(0);
            if ($returnItemFirst === false) {
                continue;
            }

            $returnTransfer->setMerchantReference(
                $returnItemFirst->getOrderItemOrFail()->getMerchantReference()
            );
        }

        return $returnCollectionTransfer;
    }
}
