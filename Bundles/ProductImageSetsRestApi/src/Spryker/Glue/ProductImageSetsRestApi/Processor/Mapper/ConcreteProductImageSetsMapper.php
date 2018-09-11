<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductConcreteImageStorageTransfer;
use Generated\Shared\Transfer\RestProductImageSetsAttributesTransfer;

class ConcreteProductImageSetsMapper implements ConcreteProductImageSetsMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteImageStorageTransfer $productConcreteImageStorageTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductImageSetsAttributesTransfer
     */
    public function mapProductConcreteImageStorageTransferToRestProductImageSetsAttributesTransfer(
        ProductConcreteImageStorageTransfer $productConcreteImageStorageTransfer
    ): RestProductImageSetsAttributesTransfer {
        return (new RestProductImageSetsAttributesTransfer())->fromArray(
            $productConcreteImageStorageTransfer->toArray(),
            true
        );
    }
}
