<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailability;

use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductOfferServicePointAvailability\ProductOfferServicePointAvailabilityFactory getFactory()
 */
class ProductOfferServicePointAvailabilityClient extends AbstractClient implements ProductOfferServicePointAvailabilityClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer
     */
    public function getProductOfferServicePointAvailabilityCollection(
        ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
    ): ProductOfferServicePointAvailabilityCollectionTransfer {
        return $this->getFactory()
            ->createProductOfferServicePointAvailabilityReader()
            ->getProductOfferServicePointAvailabilityCollection($productOfferServicePointAvailabilityCriteriaTransfer);
    }
}
