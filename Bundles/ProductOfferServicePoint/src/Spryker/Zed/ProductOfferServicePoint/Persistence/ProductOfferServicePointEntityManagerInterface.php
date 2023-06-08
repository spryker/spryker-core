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
     * @param string $productOfferReference
     * @param list<string> $serviceUuids
     *
     * @return void
     */
    public function deleteProductOfferServicesByProductOfferReferenceAndServiceUuids(string $productOfferReference, array $serviceUuids): void;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceTransfer $productOfferServiceTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceTransfer
     */
    public function createProductOfferService(ProductOfferServiceTransfer $productOfferServiceTransfer): ProductOfferServiceTransfer;
}
