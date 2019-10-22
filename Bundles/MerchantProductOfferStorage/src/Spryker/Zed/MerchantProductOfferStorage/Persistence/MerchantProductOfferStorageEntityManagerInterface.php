<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Persistence;

use Generated\Shared\Transfer\ProductConcreteProductOffersStorageTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;

interface MerchantProductOfferStorageEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteProductOffersStorageTransfer $productConcreteProductOffersStorageTransfer
     *
     * @return void
     */
    public function saveProductConcreteProductOffersStorage(ProductConcreteProductOffersStorageTransfer $productConcreteProductOffersStorageTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     *
     * @return void
     */
    public function saveProductOfferStorage(ProductOfferStorageTransfer $productOfferStorageTransfer): void;

    /**
     * @param string[] $concreteSkus
     *
     * @return void
     */
    public function deleteProductConcreteProductOffersStorage(array $concreteSkus): void;

    /**
     * @param string[] $productOfferReferences
     *
     * @return void
     */
    public function deleteProductOfferStorage(array $productOfferReferences): void;
}
