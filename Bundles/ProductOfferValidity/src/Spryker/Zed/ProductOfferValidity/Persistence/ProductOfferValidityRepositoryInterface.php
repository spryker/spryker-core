<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferValidity\Persistence;

use Generated\Shared\Transfer\ProductOfferValidityCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferValidityTransfer;

interface ProductOfferValidityRepositoryInterface
{
    /**
     * @return \Generated\Shared\Transfer\ProductOfferValidityCollectionTransfer
     */
    public function getActivatableProductOffers(): ProductOfferValidityCollectionTransfer;

    /**
     * @return \Generated\Shared\Transfer\ProductOfferValidityCollectionTransfer
     */
    public function getDeactivatableProductOffers(): ProductOfferValidityCollectionTransfer;

    /**
     * @param int $idProductOffer
     *
     * @return \Generated\Shared\Transfer\ProductOfferValidityTransfer|null
     */
    public function findProductOfferValidityByIdProductOffer(int $idProductOffer): ?ProductOfferValidityTransfer;
}
