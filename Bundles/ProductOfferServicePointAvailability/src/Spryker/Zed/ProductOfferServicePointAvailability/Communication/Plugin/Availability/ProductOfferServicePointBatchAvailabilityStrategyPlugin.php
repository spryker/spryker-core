<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointAvailability\Communication\Plugin\Availability;

use Generated\Shared\Transfer\SellableItemsRequestTransfer;
use Generated\Shared\Transfer\SellableItemsResponseTransfer;
use Spryker\Zed\AvailabilityExtension\Dependency\Plugin\BatchAvailabilityStrategyPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductOfferServicePointAvailability\Business\ProductOfferServicePointAvailabilityFacadeInterface getFacade()
 */
class ProductOfferServicePointBatchAvailabilityStrategyPlugin extends AbstractPlugin implements BatchAvailabilityStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     * - Filters out sellable item requests without product offer reference or service point.
     * - Checks if sellable item request has valid service point for the provided product offer reference.
     * - Returns `SellableItemsResponseTransfer` filled with not valid sellable item responses.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SellableItemsRequestTransfer $sellableItemsRequestTransfer
     * @param \Generated\Shared\Transfer\SellableItemsResponseTransfer $sellableItemsResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemsResponseTransfer
     */
    public function findItemsAvailabilityForStore(
        SellableItemsRequestTransfer $sellableItemsRequestTransfer,
        SellableItemsResponseTransfer $sellableItemsResponseTransfer
    ): SellableItemsResponseTransfer {
        return $this->getFacade()->getItemsAvailabilityForStore(
            $sellableItemsRequestTransfer,
            $sellableItemsResponseTransfer,
        );
    }
}
