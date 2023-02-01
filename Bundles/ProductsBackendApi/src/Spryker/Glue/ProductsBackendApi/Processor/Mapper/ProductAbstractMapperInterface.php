<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsBackendApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ApiProductsAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;

interface ProductAbstractMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiProductsAttributesTransfer $apiProductsAttributesTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function mapApiProductsAttributesTransferToProductAbstractTransfer(
        ApiProductsAttributesTransfer $apiProductsAttributesTransfer,
        ProductAbstractTransfer $productAbstractTransfer
    ): ProductAbstractTransfer;

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ApiProductsProductConcreteAttributesTransfer> $apiProductsProductConcreteAttributesTransfers
     * @param string $productAbstractSku
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function mapApiProductsProductConcreteAttributesTransfersToProductConcreteTransfers(
        ArrayObject $apiProductsProductConcreteAttributesTransfers,
        string $productAbstractSku
    ): array;
}
