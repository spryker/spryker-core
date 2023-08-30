<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointAvailability\Business;

use Generated\Shared\Transfer\SellableItemsRequestTransfer;
use Generated\Shared\Transfer\SellableItemsResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOfferServicePointAvailability\Business\ProductOfferServicePointAvailabilityBusinessFactory getFactory()
 */
class ProductOfferServicePointAvailabilityFacade extends AbstractFacade implements ProductOfferServicePointAvailabilityFacadeInterface
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
            ->createProductOfferServicePointAvailabilityReader()
            ->getItemsAvailabilityForStore($sellableItemsRequestTransfer, $sellableItemsResponseTransfer);
    }
}
