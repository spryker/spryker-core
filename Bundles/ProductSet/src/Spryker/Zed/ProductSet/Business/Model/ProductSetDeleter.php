<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model;

use Generated\Shared\Transfer\ProductSetTransfer;
use Orm\Zed\ProductSet\Persistence\SpyProductSet;
use Spryker\Zed\ProductSet\Business\Model\Data\ProductSetDataDeleterInterface;
use Spryker\Zed\ProductSet\Business\Model\Touch\ProductSetTouchInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductSetDeleter implements ProductSetDeleterInterface
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductSet\Business\Model\ProductSetEntityReaderInterface
     */
    protected $productSetEntityReader;

    /**
     * @var \Spryker\Zed\ProductSet\Business\Model\Data\ProductSetDataDeleterInterface
     */
    protected $productSetDataDeleter;

    /**
     * @var \Spryker\Zed\ProductSet\Business\Model\Touch\ProductSetTouchInterface
     */
    protected $productSetTouch;

    /**
     * @param \Spryker\Zed\ProductSet\Business\Model\ProductSetEntityReaderInterface $productSetEntityReader
     * @param \Spryker\Zed\ProductSet\Business\Model\Data\ProductSetDataDeleterInterface $productSetDataDeleter
     * @param \Spryker\Zed\ProductSet\Business\Model\Touch\ProductSetTouchInterface $productSetTouch
     */
    public function __construct(
        ProductSetEntityReaderInterface $productSetEntityReader,
        ProductSetDataDeleterInterface $productSetDataDeleter,
        ProductSetTouchInterface $productSetTouch
    ) {
        $this->productSetEntityReader = $productSetEntityReader;
        $this->productSetDataDeleter = $productSetDataDeleter;
        $this->productSetTouch = $productSetTouch;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return void
     */
    public function deleteProductSet(ProductSetTransfer $productSetTransfer)
    {
        $this->assertProductSetForDelete($productSetTransfer);

        $this->handleDatabaseTransaction(function () use ($productSetTransfer) {
            $this->executeUpdateProductSetTransaction($productSetTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return void
     */
    protected function assertProductSetForDelete(ProductSetTransfer $productSetTransfer)
    {
        $productSetTransfer->requireIdProductSet();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return void
     */
    protected function executeUpdateProductSetTransaction(ProductSetTransfer $productSetTransfer)
    {
        $productSetEntity = $this->productSetEntityReader->getProductSetEntity($productSetTransfer);

        $this->productSetDataDeleter->deleteProductSetData($productSetEntity);
        $this->deleteProductAbstractSetEntities($productSetEntity);
        $this->deleteProductSetEntity($productSetEntity);
        $this->touchProductSet($productSetTransfer);
    }

    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSet $productSetEntity
     *
     * @return void
     */
    protected function deleteProductAbstractSetEntities(SpyProductSet $productSetEntity)
    {
        $productSetEntity->getSpyProductAbstractSets()->delete();
    }

    /**
     * @param \Orm\Zed\ProductSet\Persistence\SpyProductSet $productSetEntity
     *
     * @return void
     */
    protected function deleteProductSetEntity(SpyProductSet $productSetEntity)
    {
        $productSetEntity->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductSetTransfer $productSetTransfer
     *
     * @return void
     */
    protected function touchProductSet(ProductSetTransfer $productSetTransfer)
    {
        $this->productSetTouch->touchProductSetDeleted($productSetTransfer);
    }

}
