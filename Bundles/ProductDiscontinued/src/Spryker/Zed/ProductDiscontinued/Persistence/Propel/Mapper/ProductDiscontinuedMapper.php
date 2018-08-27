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
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedTransfer
     */
    public function mapProductDiscontinuedTransfer(SpyProductDiscontinued $productDiscontinuedEntity): ProductDiscontinuedTransfer
    {
        $productDiscontinuedTransfer = (new ProductDiscontinuedTransfer())
            ->fromArray($productDiscontinuedEntity->toArray(), true);
        if ($productDiscontinuedEntity->getProduct()) {
            $productDiscontinuedTransfer->setSku(
                $productDiscontinuedEntity->getProduct()->getSku()
            );
        }
        if ($productDiscontinuedEntity->getSpyProductDiscontinuedNotes()) {
            $productDiscontinuedTransfer->setProductDiscontinuedNotes(
                $this->mapProductDiscontinuedNotes($productDiscontinuedEntity)
            );
        }

        return $productDiscontinuedTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     * @param \Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinued $productDiscontinuedEntity
     *
     * @return \Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinued
     */
    public function mapTransferToEntity(
        ProductDiscontinuedTransfer $productDiscontinuedTransfer,
        SpyProductDiscontinued $productDiscontinuedEntity
    ): SpyProductDiscontinued {
        $productDiscontinuedEntity->fromArray($productDiscontinuedTransfer->toArray());

        return $productDiscontinuedEntity;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $productDiscontinuedEntityCollection
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedCollectionTransfer
     */
    public function mapTransferCollection(ObjectCollection $productDiscontinuedEntityCollection): ProductDiscontinuedCollectionTransfer
    {
        $productDiscontinuedCollectionTransfer = new ProductDiscontinuedCollectionTransfer();
        foreach ($productDiscontinuedEntityCollection as $productDiscontinuedEntity) {
            $productDiscontinuedCollectionTransfer->addDiscontinuedProduct(
                $this->mapProductDiscontinuedTransfer(
                    $productDiscontinuedEntity
                )
            );
        }

        return $productDiscontinuedCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinued $productDiscontinuedEntity
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductDiscontinuedNoteTransfer[]
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
