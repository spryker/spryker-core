<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model;

use Generated\Shared\Transfer\ProductSetTransfer;
use Orm\Zed\ProductSet\Persistence\SpyProductSet;
use Spryker\Zed\ProductSet\Business\Model\Touch\ProductSetTouchInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductSetOrganizer implements ProductSetOrganizerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductSet\Business\Model\ProductSetEntityReaderInterface
     */
    protected $productSetEntityReader;

    /**
     * @var \Spryker\Zed\ProductSet\Business\Model\Touch\ProductSetTouchInterface
     */
    protected $productSetTouch;

    /**
     * @param \Spryker\Zed\ProductSet\Business\Model\ProductSetEntityReaderInterface $productSetEntityReader
     * @param \Spryker\Zed\ProductSet\Business\Model\Touch\ProductSetTouchInterface $productSetTouch
     */
    public function __construct(ProductSetEntityReaderInterface $productSetEntityReader, ProductSetTouchInterface $productSetTouch)
    {
        $this->productSetEntityReader = $productSetEntityReader;
        $this->productSetTouch = $productSetTouch;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer[] $productSetTransfers
     *
     * @return void
     */
    public function reorderProductSets(array $productSetTransfers)
    {
        $this->handleDatabaseTransaction(function () use ($productSetTransfers) {
            $this->executeReorderProductSetsTransaction($productSetTransfers);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer[] $productSetTransfers
     *
     * @return void
     */
    protected function executeReorderProductSetsTransaction(array $productSetTransfers)
    {
        foreach ($productSetTransfers as $productSetTransfer) {
            $this->assertProductSetTransferForReorder($productSetTransfer);

            $productSetTransfer = $this->updateProductSet($productSetTransfer);

            $this->productSetTouch->touchProductSetByStatus($productSetTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return void
     */
    protected function assertProductSetTransferForReorder(ProductSetTransfer $productSetTransfer)
    {
        $productSetTransfer
            ->requireIdProductSet()
            ->requireWeight();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return \Generated\Shared\Transfer\ProductSetTransfer
     */
    protected function updateProductSet(ProductSetTransfer $productSetTransfer)
    {
        $productSetEntity = $this->productSetEntityReader->getProductSetEntity($productSetTransfer);

        $this->updateProductSetEntity($productSetEntity, $productSetTransfer);

        $productSetTransfer->fromArray($productSetEntity->toArray(), true);

        return $productSetTransfer;
    }

    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSet $productSetEntity
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return void
     */
    protected function updateProductSetEntity(SpyProductSet $productSetEntity, ProductSetTransfer $productSetTransfer)
    {
        $productSetEntity->setWeight($productSetTransfer->getWeight());
        $productSetEntity->save();
    }
}
