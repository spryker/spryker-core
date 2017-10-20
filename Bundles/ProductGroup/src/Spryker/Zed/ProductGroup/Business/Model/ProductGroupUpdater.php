<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroup\Business\Model;

use Generated\Shared\Transfer\ProductGroupTransfer;
use Orm\Zed\ProductGroup\Persistence\SpyProductAbstractGroup;
use Orm\Zed\ProductGroup\Persistence\SpyProductGroup;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductGroupUpdater implements ProductGroupUpdaterInterface
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
     * @return \Generated\Shared\Transfer\ProductGroupTransfer
     */
    public function updateProductGroup(ProductGroupTransfer $productGroupTransfer)
    {
        $this->assertProductGroupForUpdate($productGroupTransfer);

        return $this->handleDatabaseTransaction(function () use ($productGroupTransfer) {
            return $this->executeUpdateProductGroupTransaction($productGroupTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ProductGroupTransfer
     */
    public function extendProductGroup(ProductGroupTransfer $productGroupTransfer)
    {
        $this->assertProductGroupForUpdate($productGroupTransfer);

        return $this->handleDatabaseTransaction(function () use ($productGroupTransfer) {
            return $this->executeUpdateProductGroupTransaction($productGroupTransfer);
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
     * @return \Generated\Shared\Transfer\ProductGroupTransfer
     */
    protected function executeUpdateProductGroupTransaction(ProductGroupTransfer $productGroupTransfer)
    {
        $productGroupEntity = $this->productGroupEntityReader->findProductGroupEntity($productGroupTransfer);

        $this->cleanProductGroupEntity($productGroupEntity);
        $this->saveProductGroupEntity($productGroupEntity, $productGroupTransfer);
        $this->touchProductGroup($productGroupTransfer);

        return $productGroupTransfer;
    }

    /**
     * @param \Orm\Zed\ProductGroup\Persistence\SpyProductGroup $productGroupEntity
     *
     * @return void
     */
    protected function cleanProductGroupEntity(SpyProductGroup $productGroupEntity)
    {
        $this->touchProductAbstractGroupsDeleted($productGroupEntity);
        $productGroupEntity->getSpyProductAbstractGroups()->delete();
        $productGroupEntity->clearSpyProductAbstractGroups();
    }

    /**
     * @param \Orm\Zed\ProductGroup\Persistence\SpyProductGroup $productGroupEntity
     *
     * @return void
     */
    protected function touchProductAbstractGroupsDeleted(SpyProductGroup $productGroupEntity)
    {
        $productGroupTransfer = new ProductGroupTransfer();
        $productGroupTransfer->setIdProductGroup($productGroupEntity->getIdProductGroup());

        foreach ($productGroupEntity->getSpyProductAbstractGroups() as $productAbstractGroupEntity) {
            $productGroupTransfer->addIdProductAbstract($productAbstractGroupEntity->getFkProductAbstract());
        }

        $this->productGroupTouch->touchProductAbstractGroupsDeleted($productGroupTransfer);
    }

    /**
     * @param \Orm\Zed\ProductGroup\Persistence\SpyProductGroup $productGroupEntity
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return void
     */
    protected function saveProductGroupEntity(SpyProductGroup $productGroupEntity, ProductGroupTransfer $productGroupTransfer)
    {
        foreach ($productGroupTransfer->getIdProductAbstracts() as $position => $idProductAbstract) {
            $productAbstractGroupEntity = $this->createProductAbstractGroupEntity($idProductAbstract, $position);
            $productGroupEntity->addSpyProductAbstractGroup($productAbstractGroupEntity);
        }

        $productGroupEntity->save();
    }

    /**
     * @param int $idProductAbstract
     * @param int $position
     *
     * @return \Orm\Zed\ProductGroup\Persistence\SpyProductAbstractGroup
     */
    protected function createProductAbstractGroupEntity($idProductAbstract, $position)
    {
        $productAbstractGroupEntity = new SpyProductAbstractGroup();
        $productAbstractGroupEntity
            ->setFkProductAbstract($idProductAbstract)
            ->setPosition($position);

        return $productAbstractGroupEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return void
     */
    protected function touchProductGroup(ProductGroupTransfer $productGroupTransfer)
    {
        $this->productGroupTouch->touchProductGroupActive($productGroupTransfer);
        $this->productGroupTouch->touchProductAbstractGroupsActive($productGroupTransfer);
    }
}
