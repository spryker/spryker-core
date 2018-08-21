<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductAbstractImageStorageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

interface AbstractProductImageSetsMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductAbstractImageStorageTransfer $productAbstractImageStorageTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapAbstractProductImageSetsTransferToRestResource(ProductAbstractImageStorageTransfer $productAbstractImageStorageTransfer): RestResourceInterface;
}
