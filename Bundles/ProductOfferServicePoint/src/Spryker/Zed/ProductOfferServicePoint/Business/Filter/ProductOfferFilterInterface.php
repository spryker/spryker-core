<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferServiceCollectionResponseTransfer;

interface ProductOfferFilterInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $validProductOfferTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $invalidProductOfferTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer>
     */
    public function mergeProductOffers(
        ArrayObject $validProductOfferTransfers,
        ArrayObject $invalidProductOfferTransfers
    ): ArrayObject;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceCollectionResponseTransfer $productOfferServiceCollectionResponseTransfer
     *
     * @return array<\ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer>>
     */
    public function filterProductOffersByValidity(
        ProductOfferServiceCollectionResponseTransfer $productOfferServiceCollectionResponseTransfer
    ): array;
}
