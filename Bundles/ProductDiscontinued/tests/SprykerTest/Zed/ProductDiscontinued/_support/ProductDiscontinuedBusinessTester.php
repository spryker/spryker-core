<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductDiscontinued;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ProductDiscontinuedBuilder;
use Generated\Shared\DataBuilder\ProductDiscontinuedNoteBuilder;
use Generated\Shared\Transfer\ProductDiscontinuedNoteTransfer;
use Generated\Shared\Transfer\ProductDiscontinuedTransfer;
use Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinued;
use Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinuedNote;
use Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinuedNoteQuery;
use Orm\Zed\ProductDiscontinued\Persistence\SpyProductDiscontinuedQuery;
use Spryker\Zed\Product\Business\ProductFacadeInterface;

/**
 * @method \Spryker\Zed\ProductDiscontinued\Business\ProductDiscontinuedFacadeInterface getFacade()
 */
class ProductDiscontinuedBusinessTester extends Actor
{
    use _generated\ProductDiscontinuedBusinessTesterActions;

    /**
     * @return \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    public function getProductFacade(): ProductFacadeInterface
    {
        return $this->getLocator()->product()->facade();
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedTransfer
     */
    public function createProductDiscontinued(array $override = []): ProductDiscontinuedTransfer
    {
        $productConcreteTransfer = $this->haveProduct();
        $productDiscontinuedTransfer = (new ProductDiscontinuedBuilder($override))->build();
        $productDiscontinuedTransfer
            ->setFkProduct($productConcreteTransfer->getIdProductConcrete())
            ->setSku($productConcreteTransfer->getSku());

        $productDiscontinuedEntity = (new SpyProductDiscontinued())
            ->fromArray($productDiscontinuedTransfer->toArray());
        $productDiscontinuedEntity->save();

        return $productDiscontinuedTransfer
            ->setIdProductDiscontinued($productDiscontinuedEntity->getIdProductDiscontinued());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedNoteTransfer
     */
    public function createProductDiscontinuedNote(
        ProductDiscontinuedTransfer $productDiscontinuedTransfer,
        array $override = []
    ): ProductDiscontinuedNoteTransfer {
        $productDiscontinuedNoteTransfer = (new ProductDiscontinuedNoteBuilder($override))->build();
        $productDiscontinuedNoteTransfer->setFkProductDiscontinued($productDiscontinuedTransfer->getIdProductDiscontinued());

        $productDiscontinuedNoteEntity = (new SpyProductDiscontinuedNote())->fromArray($productDiscontinuedNoteTransfer->toArray());
        $productDiscontinuedNoteEntity->save();

        return $productDiscontinuedNoteTransfer
            ->setIdProductDiscontinuedNote($productDiscontinuedNoteEntity->getIdProductDiscontinuedNote());
    }

    /**
     * @return void
     */
    public function ensureProductDiscontinuedTableIsEmpty(): void
    {
        SpyProductDiscontinuedNoteQuery::create()->deleteAll();
        SpyProductDiscontinuedQuery::create()->deleteAll();
    }
}
