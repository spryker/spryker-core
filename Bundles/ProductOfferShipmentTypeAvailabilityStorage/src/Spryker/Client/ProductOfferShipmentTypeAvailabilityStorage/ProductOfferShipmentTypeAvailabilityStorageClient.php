<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferShipmentTypeAvailabilityStorage;

use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductOfferShipmentTypeAvailabilityStorage\ProductOfferShipmentTypeAvailabilityStorageFactory getFactory()
 */
class ProductOfferShipmentTypeAvailabilityStorageClient extends AbstractClient implements ProductOfferShipmentTypeAvailabilityStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer $productOfferServicePointAvailabilityCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer
     */
    public function filterProductOfferServicePointAvailabilityCollection(
        ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer,
        ProductOfferServicePointAvailabilityCollectionTransfer $productOfferServicePointAvailabilityCollectionTransfer
    ): ProductOfferServicePointAvailabilityCollectionTransfer {
        return $this->getFactory()
            ->createProductOfferServicePointAvailabilityFilter()
            ->filterProductOfferServicePointAvailabilityCollection(
                $productOfferServicePointAvailabilityCriteriaTransfer,
                $productOfferServicePointAvailabilityCollectionTransfer,
            );
    }
}
