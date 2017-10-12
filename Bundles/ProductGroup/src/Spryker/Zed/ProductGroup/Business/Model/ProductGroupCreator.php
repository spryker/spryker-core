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

class ProductGroupCreator implements ProductGroupCreatorInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductGroup\Business\Model\ProductGroupTouchInterface
     */
    protected $productGroupTouch;

    /**
     * @param \Spryker\Zed\ProductGroup\Business\Model\ProductGroupTouchInterface $productGroupTouch
     */
    public function __construct(ProductGroupTouchInterface $productGroupTouch)
    {
        $this->productGroupTouch = $productGroupTouch;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ProductGroupTransfer
     */
    public function createProductGroup(ProductGroupTransfer $productGroupTransfer)
    {
        if (!$productGroupTransfer->getIdProductAbstracts()) {
            return $productGroupTransfer;
        }

        return $this->handleDatabaseTransaction(function () use ($productGroupTransfer) {
            return $this->executeCreateProductGroupTransaction($productGroupTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return void
     */
    protected function assertProductGroupForCreate(ProductGroupTransfer $productGroupTransfer)
    {
        $productGroupTransfer->requireIdProductAbstracts();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ProductGroupTransfer
     */
    protected function executeCreateProductGroupTransaction(ProductGroupTransfer $productGroupTransfer)
    {
        $productGroupEntity = $this->createProductGroupEntity($productGroupTransfer);
        $productGroupTransfer->setIdProductGroup($productGroupEntity->getIdProductGroup());

        $this->touchProductGroup($productGroupTransfer);

        return $productGroupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return \Orm\Zed\ProductGroup\Persistence\SpyProductGroup
     */
    protected function createProductGroupEntity(ProductGroupTransfer $productGroupTransfer)
    {
        $productGroupEntity = new SpyProductGroup();

        foreach ($productGroupTransfer->getIdProductAbstracts() as $position => $idProductAbstract) {
            $productAbstractGroupEntity = $this->createProductAbstractGroupEntity($idProductAbstract, $position);
            $productGroupEntity->addSpyProductAbstractGroup($productAbstractGroupEntity);
        }

        $productGroupEntity->save();

        return $productGroupEntity;
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
