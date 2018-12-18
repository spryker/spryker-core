<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAlternativesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestAlternativeProductsAttributesTransfer;

interface AlternativeProductMapperInterface
{
    /**
     * @param array $productAbstractStorageData
     * @param \Generated\Shared\Transfer\RestAlternativeProductsAttributesTransfer $restAlternativeProductsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestAlternativeProductsAttributesTransfer
     */
    public function mapProductAbstractStorageDataToRestAlternativeProductsAttributesTransfer(
        array $productAbstractStorageData,
        RestAlternativeProductsAttributesTransfer $restAlternativeProductsAttributesTransfer
    ): RestAlternativeProductsAttributesTransfer;

    /**
     * @param array $productConcreteStorageData
     * @param \Generated\Shared\Transfer\RestAlternativeProductsAttributesTransfer $restAlternativeProductsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestAlternativeProductsAttributesTransfer
     */
    public function mapProductConcreteStorageDataToRestAlternativeProductsAttributesTransfer(
        array $productConcreteStorageData,
        RestAlternativeProductsAttributesTransfer $restAlternativeProductsAttributesTransfer
    ): RestAlternativeProductsAttributesTransfer;
}
