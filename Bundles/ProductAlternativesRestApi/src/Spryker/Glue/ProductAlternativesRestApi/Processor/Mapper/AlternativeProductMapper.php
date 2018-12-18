<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAlternativesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestAlternativeProductsAttributesTransfer;

class AlternativeProductMapper implements AlternativeProductMapperInterface
{
    protected const KEY_SKU = 'sku';

    /**
     * @param array $productAbstractStorageData
     * @param \Generated\Shared\Transfer\RestAlternativeProductsAttributesTransfer $restAlternativeProductsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestAlternativeProductsAttributesTransfer
     */
    public function mapProductAbstractStorageDataToRestAlternativeProductsAttributesTransfer(
        array $productAbstractStorageData,
        RestAlternativeProductsAttributesTransfer $restAlternativeProductsAttributesTransfer
    ): RestAlternativeProductsAttributesTransfer {
        $restAlternativeProductsAttributesTransfer->addAbstractProductId($productAbstractStorageData[static::KEY_SKU]);

        return $restAlternativeProductsAttributesTransfer;
    }

    /**
     * @param array $productConcreteStorageData
     * @param \Generated\Shared\Transfer\RestAlternativeProductsAttributesTransfer $restAlternativeProductsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestAlternativeProductsAttributesTransfer
     */
    public function mapProductConcreteStorageDataToRestAlternativeProductsAttributesTransfer(
        array $productConcreteStorageData,
        RestAlternativeProductsAttributesTransfer $restAlternativeProductsAttributesTransfer
    ): RestAlternativeProductsAttributesTransfer {
        $restAlternativeProductsAttributesTransfer->addConcreteProductId($productConcreteStorageData[static::KEY_SKU]);

        return $restAlternativeProductsAttributesTransfer;
    }
}
