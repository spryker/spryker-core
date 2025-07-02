<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Persistence\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductClassCollectionTransfer;
use Generated\Shared\Transfer\ProductClassTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpyProductClass;

class ProductClassMapper
{
    /**
     * @param \Orm\Zed\SelfServicePortal\Persistence\SpyProductClass $productClassEntity
     *
     * @return \Generated\Shared\Transfer\ProductClassTransfer
     */
    public function mapProductClassEntityToProductClassTransfer(
        SpyProductClass $productClassEntity
    ): ProductClassTransfer {
        return (new ProductClassTransfer())
            ->fromArray($productClassEntity->toArray(), true);
    }

    /**
     * @param array<\Orm\Zed\SelfServicePortal\Persistence\SpyProductClass> $productClassEntities
     * @param \Generated\Shared\Transfer\ProductClassCollectionTransfer $productClassCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductClassCollectionTransfer
     */
    public function mapProductClassEntitiesToProductClassCollectionTransfer(
        array $productClassEntities,
        ProductClassCollectionTransfer $productClassCollectionTransfer
    ): ProductClassCollectionTransfer {
        $productClassTransfers = [];

        foreach ($productClassEntities as $productClassEntity) {
            $productClassTransfers[] = $this->mapProductClassEntityToProductClassTransfer($productClassEntity);
        }

        return $productClassCollectionTransfer->setProductClasses(new ArrayObject($productClassTransfers));
    }
}
