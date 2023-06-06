<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Communication\Plugin\ProductOffer;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferExtension\Dependency\Plugin\ProductOfferPostUpdatePluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferShipmentType\Business\ProductOfferShipmentTypeFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferShipmentType\ProductOfferShipmentTypeConfig getConfig()
 */
class ShipmentTypeProductOfferPostUpdatePlugin extends AbstractPlugin implements ProductOfferPostUpdatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ProductOfferTransfer.productOfferReference` to be set.
     * - Requires `ShipmentTypeTransfer.shipmentTypeUuid` to be set for each `ShipmentTypeTransfer` in `ProductOfferTransfer.shipmentTypes` collection.
     * - Deletes redundant product offer shipment types from Persistence.
     * - Persists missed product offer shipment types to Persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function execute(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        return $this->getFacade()->updateProductOfferShipmentTypes($productOfferTransfer);
    }
}
