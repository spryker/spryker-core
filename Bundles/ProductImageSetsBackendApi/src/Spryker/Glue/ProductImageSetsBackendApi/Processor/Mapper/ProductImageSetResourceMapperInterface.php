<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductImageSetResourceCollectionTransfer;

interface ProductImageSetResourceMapperInterface
{
    /**
     * @param array<string, list<\Generated\Shared\Transfer\ProductImageSetTransfer>> $productImageSetTransfersGroupedBySku
     * @param \Generated\Shared\Transfer\ProductImageSetResourceCollectionTransfer $productImageSetResourceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductImageSetResourceCollectionTransfer
     */
    public function mapProductImageSetCollectionToProductImageSetResourceCollection(
        array $productImageSetTransfersGroupedBySku,
        ProductImageSetResourceCollectionTransfer $productImageSetResourceCollectionTransfer
    ): ProductImageSetResourceCollectionTransfer;
}
