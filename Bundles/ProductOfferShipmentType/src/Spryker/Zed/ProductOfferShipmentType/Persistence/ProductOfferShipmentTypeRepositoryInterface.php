<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Persistence;

use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCriteriaTransfer;

interface ProductOfferShipmentTypeRepositoryInterface
{
    /**
     * @param int $idProductOffer
     *
     * @return array<int>
     */
    public function getShipmentTypeIdsByIdProductOffer(int $idProductOffer): array;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCriteriaTransfer $productOfferShipmentTypeCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer
     */
    public function getProductOfferShipmentTypeCollection(
        ProductOfferShipmentTypeCriteriaTransfer $productOfferShipmentTypeCriteriaTransfer
    ): ProductOfferShipmentTypeCollectionTransfer;
}
