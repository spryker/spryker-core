<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroup\Business\Model;

use Generated\Shared\Transfer\ProductGroupTransfer;
use Orm\Zed\ProductGroup\Persistence\SpyProductGroup;
use Spryker\Zed\ProductGroup\Business\Exception\ProductGroupNotFoundException;
use Spryker\Zed\ProductGroup\Persistence\ProductGroupQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductGroupDeleter implements ProductGroupDeleterInterface
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductGroup\Persistence\ProductGroupQueryContainerInterface
     */
    protected $productGroupQueryContainer;

    /**
     * @var \Spryker\Zed\ProductGroup\Business\Model\ProductGroupTouchInterface
     */
    protected $productGroupTouch;

    /**
     * @param \Spryker\Zed\ProductGroup\Persistence\ProductGroupQueryContainerInterface $productGroupQueryContainer
     * @param \Spryker\Zed\ProductGroup\Business\Model\ProductGroupTouchInterface $productGroupTouch
     */
    public function __construct(ProductGroupQueryContainerInterface $productGroupQueryContainer, ProductGroupTouchInterface $productGroupTouch)
    {
        $this->productGroupQueryContainer = $productGroupQueryContainer;
        $this->productGroupTouch = $productGroupTouch;
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
        $productGroupEntity = $this->findProductGroupEntity($productGroupTransfer);

        $this->deleteProductGroupEntity($productGroupEntity);
        $this->touchProductGroup($productGroupTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @throws \Spryker\Zed\ProductGroup\Business\Exception\ProductGroupNotFoundException
     *
     * @return \Orm\Zed\ProductGroup\Persistence\SpyProductGroup
     */
    protected function findProductGroupEntity(ProductGroupTransfer $productGroupTransfer)
    {
        $productGroupEntity = $this->productGroupQueryContainer
            ->queryProductGroupById($productGroupTransfer->getIdProductGroup())
            ->findOne();

        if (!$productGroupEntity) {
            throw new ProductGroupNotFoundException(sprintf(
                'Product group with ID "%d" not found.',
                $productGroupTransfer->getIdProductGroup()
            ));
        }

        return $productGroupEntity;
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
