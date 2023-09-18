<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailability\Business;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOfferAvailability\Business\ProductOfferAvailabilityBusinessFactory getFactory()
 */
class ProductOfferAvailabilityFacade extends AbstractFacade implements ProductOfferAvailabilityFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findProductConcreteAvailability(
        ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
    ): ?ProductConcreteAvailabilityTransfer {
        return $this->getFactory()
            ->createProductOfferAvailabilityProvider()
            ->findProductConcreteAvailability($productOfferAvailabilityRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderItemsWithMerchantStockAddressSplitByStockAvailability(OrderTransfer $orderTransfer): OrderTransfer
    {
        return $this->getFactory()
            ->createItemExpander()
            ->expandOrderItemsWithMerchantStockAddressSplitByStockAvailability($orderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function expandCalculableObjectItemsWithMerchantStockAddressSplitByStockAvailability(
        CalculableObjectTransfer $quoteTransfer
    ): CalculableObjectTransfer {
        return $this->getFactory()
            ->createItemExpander()
            ->expandCalculableObjectItemsWithMerchantStockAddressSplitByStockAvailability($quoteTransfer);
    }
}
