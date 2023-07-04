<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Persistence;

use Generated\Shared\Transfer\ProductOfferServiceTransfer;

interface ProductOfferServicePointEntityManagerInterface
{
    /**
     * @param int $idProductOffer
     * @param list<int> $serviceIds
     *
     * @return void
     */
    public function deleteProductOfferServicesByIdProductOfferAndServiceIds(int $idProductOffer, array $serviceIds): void;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceTransfer $productOfferServiceTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceTransfer
     */
    public function createProductOfferService(ProductOfferServiceTransfer $productOfferServiceTransfer): ProductOfferServiceTransfer;
}
