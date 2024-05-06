<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedTransfer;
use Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinued;
use Propel\Runtime\Collection\Collection;

interface ProductDiscontinuedMapperInterface
{
    /**
     * @param \Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinued $productDiscontinuedEntity
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedTransfer
     */
    public function mapProductDiscontinuedEntityToProductDiscontinuedTransfer(
        SpyProductDiscontinued $productDiscontinuedEntity,
        ProductDiscontinuedTransfer $productDiscontinuedTransfer
    ): ProductDiscontinuedTransfer;

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinued> $productDiscontinuedEntities
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer
     */
    public function mapProductDiscontinuedEntitiesToProductDiscontinuedCollectionTransfer(
        Collection $productDiscontinuedEntities,
        ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer
    ): ProductDiscontinuedCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     * @param \Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinued $productDiscontinuedEntity
     *
     * @return \Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinued
     */
    public function mapProductDiscontinuedTransferToProductDiscontinuedEntity(
        ProductDiscontinuedTransfer $productDiscontinuedTransfer,
        SpyProductDiscontinued $productDiscontinuedEntity
    ): SpyProductDiscontinued;
}
