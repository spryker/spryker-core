<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Communication\Plugin\Cart;

use Generated\Shared\Transfer\SellableItemBatchRequestTransfer;
use Spryker\Zed\AvailabilityExtension\Dependency\Plugin\BatchAvailabilityStrategyPluginInterface;
use Spryker\Zed\AvailabilityExtension\Dependency\Plugin\SellableItemBatchResponseTransfer;

/**
 * @method \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface getFacade()
 */
class ProductConcreteBatchAvailabilityStrategyPlugin implements BatchAvailabilityStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SellableItemBatchRequestTransfer $sellableItemBatchRequestTransfer
     * @param \Generated\Shared\Transfer\SellableItemBatchRequestTransfer $sellableItemBatchResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemBatchResponseTransfer
     */
    public function findItemsAvailabilityForStore(
        SellableItemBatchRequestTransfer $sellableItemBatchRequestTransfer,
        SellableItemBatchResponseTransfer $sellableItemBatchResponseTransfer
    ): SellableItemBatchResponseTransfer {
        return $this->getFacade()->areProductConcretesSellableForStore(
            $sellableItemBatchRequestTransfer,
            $sellableItemBatchResponseTransfer
        );
    }
}
