<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Persistence\Mapper;

use Generated\Shared\Transfer\ProductAbstractMerchantTransfer;

interface ProductAbstractMerchantMapperInterface
{
    /**
     * @param array $productAbstractMerchantData
     * @param \Generated\Shared\Transfer\ProductAbstractMerchantTransfer $productAbstractMerchantTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractMerchantTransfer
     */
    public function mapProductAbstractMerchantDataToProductAbstractMerchantTransfer(
        array $productAbstractMerchantData,
        ProductAbstractMerchantTransfer $productAbstractMerchantTransfer
    ): ProductAbstractMerchantTransfer;
}
