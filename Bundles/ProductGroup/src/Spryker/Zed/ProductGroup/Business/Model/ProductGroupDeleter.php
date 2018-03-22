<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroup\Business\Model;

use Generated\Shared\Transfer\ProductGroupTransfer;
use Orm\Zed\ProductGroup\Persistence\SpyProductGroup;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductGroupDeleter implements ProductGroupDeleterInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductGroup\Business\Model\ProductGroupEntityReaderInterface
     */
    protected $productGroupEntityReader;

    /**
     * @var \Spryker\Zed\ProductGroup\Business\Model\ProductGroupTouchInterface
     */
    protected $productGroupTouch;

    /**
     * @param \Spryker\Zed\ProductGroup\Business\Model\ProductGroupEntityReaderInterface $productGroupEntityReader
     * @param \Spryker\Zed\ProductGroup\Business\Model\ProductGroupTouchInterface $productGroupTouch
     */
    public function __construct(ProductGroupEntityReaderInterface $productGroupEntityReader, ProductGroupTouchInterface $productGroupTouch)
    {
        $this->productGroupTouch = $productGroupTouch;
        $this->productGroupEntityReader = $productGroupEntityReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return void
     */
    public function deleteProductGroup(ProductGroupTransfer $productGroupTransfer)
    {
        $this->assertProductGroupForUpdate($productGroupTransfer);

        $this->handleDatabaseTransaction(function () use ($productGroupTransfer) {
            $this->executeUpdateProductGroupTransaction($productGroupTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return void
     */
    protected function assertProductGroupForUpdate(ProductGroupTransfer $productGroupTransfer)
    {
        $productGroupTransfer->requireIdProductGroup();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return void
     */
    protected function executeUpdateProductGroupTransaction(ProductGroupTransfer $productGroupTransfer)
    {
        $productGroupEntity = $this->productGroupEntityReader->findProductGroupEntity($productGroupTransfer);

        $this->deleteProductGroupEntity($productGroupEntity);
        $this->touchProductGroup($productGroupTransfer);
    }

    /**
     * @param \Orm\Zed\ProductGroup\Persistence\SpyProductGroup $productGroupEntity
     *
     * @return void
     */
    protected function deleteProductGroupEntity(SpyProductGroup $productGroupEntity)
    {
        $productGroupEntity->getSpyProductAbstractGroups()->delete();
        $productGroupEntity->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return void
     */
    protected function touchProductGroup(ProductGroupTransfer $productGroupTransfer)
    {
        $this->productGroupTouch->touchProductGroupDeleted($productGroupTransfer);
        $this->productGroupTouch->touchProductAbstractGroupsDeleted($productGroupTransfer);
    }
}
