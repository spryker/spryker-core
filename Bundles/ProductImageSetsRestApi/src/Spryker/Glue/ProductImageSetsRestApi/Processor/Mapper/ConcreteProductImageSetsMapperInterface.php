<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductConcreteImageStorageTransfer;
use Generated\Shared\Transfer\RestProductImageSetsAttributesTransfer;

interface ConcreteProductImageSetsMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteImageStorageTransfer $productAbstractImageStorageTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductImageSetsAttributesTransfer
     */
    public function mapProductConcreteImageStorageTransferToRestProductImageSetsAttributesTransfer(ProductConcreteImageStorageTransfer $productAbstractImageStorageTransfer): RestProductImageSetsAttributesTransfer;
}
