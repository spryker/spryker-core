<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;

interface ProductFormTransferMapperInterface
{
    /**
     * @param mixed[] $addProductConcreteFormData
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $defaultStoreDefaultLocaleTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function mapAddProductConcreteFormDataToProductConcreteCollectionTransfer(
        array $addProductConcreteFormData,
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer,
        LocaleTransfer $defaultStoreDefaultLocaleTransfer
    ): ProductConcreteCollectionTransfer;
}
