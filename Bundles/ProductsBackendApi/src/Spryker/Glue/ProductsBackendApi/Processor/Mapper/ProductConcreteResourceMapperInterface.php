<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteResourceCollectionTransfer;

interface ProductConcreteResourceMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteCollectionTransfer $productConcreteCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteResourceCollectionTransfer $productConcreteResourceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteResourceCollectionTransfer
     */
    public function mapProductConcreteCollectionTransferToProductConcreteResourceCollectionTransfer(
        ProductConcreteCollectionTransfer $productConcreteCollectionTransfer,
        ProductConcreteResourceCollectionTransfer $productConcreteResourceCollectionTransfer
    ): ProductConcreteResourceCollectionTransfer;
}
