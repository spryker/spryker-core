<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOptionsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer;
use Generated\Shared\Transfer\RestProductOptionsAttributesTransfer;

class ProductOptionMapper implements ProductOptionMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractOptionStorageTransfer $productAbstractOptionStorageTransfer
     * @param \Generated\Shared\Transfer\RestProductOptionsAttributesTransfer $restProductOptionsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductOptionsAttributesTransfer
     */
    public function mapProductAbstractOptionStorageTransferToRestProductOptionsAttributesTransfer(
        ProductAbstractOptionStorageTransfer $productAbstractOptionStorageTransfer,
        RestProductOptionsAttributesTransfer $restProductOptionsAttributesTransfer
    ): RestProductOptionsAttributesTransfer {
        return $restProductOptionsAttributesTransfer->fromArray(
            $productAbstractOptionStorageTransfer->toArray(),
            true
        );
    }
}
