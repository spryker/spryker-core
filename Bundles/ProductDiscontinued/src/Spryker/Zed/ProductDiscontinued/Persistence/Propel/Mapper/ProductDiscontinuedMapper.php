<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Persistence\Propel\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedNoteTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedTransfer;
use Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinued;
use Propel\Runtime\Collection\ObjectCollection;

class ProductDiscontinuedMapper implements ProductDiscontinuedMapperInterface
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
    ): ProductDiscontinuedTransfer {
        $productDiscontinuedTransfer->fromArray($productDiscontinuedEntity->toArray(), true);
        $productDiscontinuedTransfer->setSku($productDiscontinuedEntity->getProduct()->getSku());

        $productDiscontinuedEntity->initSpyProductDiscontinuedNotes(false);
        $productDiscontinuedTransfer->setProductDiscontinuedNotes(
            $this->mapProductDiscontinuedNotes($productDiscontinuedEntity),
        );

        return $productDiscontinuedTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinued> $productDiscontinuedEntities
     * @param \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer
     */
    public function mapProductDiscontinuedEntitiesToProductDiscontinuedCollectionTransfer(
        ObjectCollection $productDiscontinuedEntities,
        ProductDiscontinuedCollectionTransfer $productDiscontinuedCollectionTransfer
    ): ProductDiscontinuedCollectionTransfer {
        foreach ($productDiscontinuedEntities as $productDiscontinuedEntity) {
            $productDiscontinuedCollectionTransfer->addDiscontinuedProduct(
                $this->mapProductDiscontinuedEntityToProductDiscontinuedTransfer(
                    $productDiscontinuedEntity,
                    new ProductDiscontinuedTransfer(),
                ),
            );
        }

        return $productDiscontinuedCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     * @param \Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinued $productDiscontinuedEntity
     *
     * @return \Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinued
     */
    public function mapProductDiscontinuedTransferToProductDiscontinuedEntity(
        ProductDiscontinuedTransfer $productDiscontinuedTransfer,
        SpyProductDiscontinued $productDiscontinuedEntity
    ): SpyProductDiscontinued {
        $productDiscontinuedEntity->fromArray($productDiscontinuedTransfer->toArray());

        return $productDiscontinuedEntity;
    }

    /**
     * @param \Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinued $productDiscontinuedEntity
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductDiscontinuedNoteTransfer>
     */
    protected function mapProductDiscontinuedNotes(SpyProductDiscontinued $productDiscontinuedEntity): ArrayObject
    {
        $discontinuedNoteTransfers = [];
        foreach ($productDiscontinuedEntity->getSpyProductDiscontinuedNotes() as $discontinuedNoteEntityTransfer) {
            $discontinuedNoteTransfers[] = (new ProductDiscontinuedNoteTransfer())
                ->fromArray($discontinuedNoteEntityTransfer->toArray(), true);
        }

        return new ArrayObject($discontinuedNoteTransfers);
    }
}
