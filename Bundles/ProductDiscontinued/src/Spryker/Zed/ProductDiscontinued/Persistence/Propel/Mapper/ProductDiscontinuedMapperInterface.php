<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedTransfer;
use Generated\Shared\Transfer\SpyProductDiscontinuedEntityTransfer;
use Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinued;

interface ProductDiscontinuedMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyProductDiscontinuedEntityTransfer $productDiscontinuedEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedTransfer
     */
    public function mapProductDiscontinuedTransfer(SpyProductDiscontinuedEntityTransfer $productDiscontinuedEntityTransfer): ProductDiscontinuedTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     * @param \Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinued $productDiscontinuedEntity
     *
     * @return \Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinued
     */
    public function mapTransferToEntity(
        ProductDiscontinuedTransfer $productDiscontinuedTransfer,
        SpyProductDiscontinued $productDiscontinuedEntity
    ): SpyProductDiscontinued;

    /**
     * @param \Generated\Shared\Transfer\SpyProductDiscontinuedEntityTransfer[] $productDiscontinuedEntityTransfers
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer
     */
    public function mapTransferCollection(array $productDiscontinuedEntityTransfers): ProductDiscontinuedCollectionTransfer;
}
