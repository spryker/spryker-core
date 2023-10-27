<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Communication\Plugin\ProductOffer;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferExtension\Dependency\Plugin\ProductOfferExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferShipmentType\Business\ProductOfferShipmentTypeFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferShipmentType\ProductOfferShipmentTypeConfig getConfig()
 */
class ShipmentTypeProductOfferExpanderPlugin extends AbstractPlugin implements ProductOfferExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ProductOfferTransfer.idProductOffer` to be set.
     * - Expands `ProductOfferTransfer` with related shipment types.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function expand(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        $productOfferCollectionTransfer = $this->getFacade()->expandProductOfferCollectionWithShipmentTypes(
            (new ProductOfferCollectionTransfer())->addProductOffer($productOfferTransfer),
        );

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers */
        $productOfferTransfers = $productOfferCollectionTransfer->getProductOffers();

        return $productOfferTransfers->getIterator()->current();
    }
}
