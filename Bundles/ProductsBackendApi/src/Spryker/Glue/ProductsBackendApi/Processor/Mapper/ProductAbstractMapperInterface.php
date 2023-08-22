<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsBackendApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductsBackendApiAttributesTransfer;

interface ProductAbstractMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductsBackendApiAttributesTransfer $productsBackendApiAttributesTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function mapProductsBackendApiAttributesTransferToProductAbstractTransfer(
        ProductsBackendApiAttributesTransfer $productsBackendApiAttributesTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer;

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductConcretesBackendApiAttributesTransfer> $productConcretesBackendApiAttributesTransfers
     * @param string $productAbstractSku
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function mapProductConcretesBackendApiAttributesTransfersToProductConcreteTransfers(
        ArrayObject $productConcretesBackendApiAttributesTransfers,
        string $productAbstractSku
    ): array;
}
