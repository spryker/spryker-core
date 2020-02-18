<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferSearch\Business\Mapper;

interface ProductAbstractMerchantMapperInterface
{
    /**
     * @param array $productAbstractMerchantData
     *
     * @return \Generated\Shared\Transfer\ProductAbstractMerchantTransfer[]
     */
    public function mapProductAbstractMerchantDataToProductAbstractMerchantTransfers(array $productAbstractMerchantData): array;
}
