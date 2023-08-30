<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeAvailability\Business;

use Generated\Shared\Transfer\SellableItemsRequestTransfer;
use Generated\Shared\Transfer\SellableItemsResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\ProductOfferShipmentTypeAvailabilityBusinessFactory getFactory()
 */
class ProductOfferShipmentTypeAvailabilityFacade extends AbstractFacade implements ProductOfferShipmentTypeAvailabilityFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SellableItemsRequestTransfer $sellableItemsRequestTransfer
     * @param \Generated\Shared\Transfer\SellableItemsResponseTransfer $sellableItemsResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemsResponseTransfer
     */
    public function getItemsAvailabilityForStore(
        SellableItemsRequestTransfer $sellableItemsRequestTransfer,
        SellableItemsResponseTransfer $sellableItemsResponseTransfer
    ): SellableItemsResponseTransfer {
        return $this->getFactory()
            ->createProductOfferShipmentTypeAvailabilityReader()
            ->getItemsAvailabilityForStore($sellableItemsRequestTransfer, $sellableItemsResponseTransfer);
    }
}
