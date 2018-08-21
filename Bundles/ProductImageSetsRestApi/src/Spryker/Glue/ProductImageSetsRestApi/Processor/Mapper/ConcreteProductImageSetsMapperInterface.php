<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductConcreteImageStorageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

interface ConcreteProductImageSetsMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteImageStorageTransfer $productConcreteImageStorageTransfer
     * @param string $sku
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapConcreteProductImageSetsTransferToRestResource(ProductConcreteImageStorageTransfer $productConcreteImageStorageTransfer, string $sku): RestResourceInterface;
}
