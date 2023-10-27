<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Filter;

use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionResponseTransfer;

interface ProductOfferFilterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionResponseTransfer $productOfferShipmentTypeCollectionResponseTransfer
     *
     * @return array<\ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer>>
     */
    public function filterProductOffersByValidity(
        ProductOfferShipmentTypeCollectionResponseTransfer $productOfferShipmentTypeCollectionResponseTransfer
    ): array;
}
