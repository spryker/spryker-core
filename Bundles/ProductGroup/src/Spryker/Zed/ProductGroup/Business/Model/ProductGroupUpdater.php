<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroup\Business\Model;

use Generated\Shared\Transfer\ProductGroupTransfer;
use Orm\Zed\ProductGroup\Persistence\SpyProductAbstractGroup;
use Spryker\Zed\ProductGroup\Business\Exception\ProductGroupNotFoundException;
use Spryker\Zed\ProductGroup\Persistence\ProductGroupQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class ProductGroupUpdater implements ProductGroupUpdaterInterface
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\ProductGroup\Persistence\ProductGroupQueryContainerInterface
     */
    protected $productGroupQueryContainer;

    /**
     * @param \Spryker\Zed\ProductGroup\Persistence\ProductGroupQueryContainerInterface $productGroupQueryContainer
     */
    public function __construct(ProductGroupQueryContainerInterface $productGroupQueryContainer)
    {
        $this->productGroupQueryContainer = $productGroupQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ProductGroupTransfer|null
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
        $productGroupEntity = $this->findProductGroupEntity($productGroupTransfer);

        $productGroupEntity->getSpyProductAbstractGroups()->delete();
        foreach ($productGroupTransfer->getIdProductAbstracts() as $position => $idProductAbstract) {
            $productAbstractGroupEntity = new SpyProductAbstractGroup();
            $productAbstractGroupEntity
                ->setFkProductAbstract($idProductAbstract)
                ->setPosition($position);

            $productGroupEntity->addSpyProductAbstractGroup($productAbstractGroupEntity);
        }
        $productGroupEntity->save();

        return $productGroupTransfer;
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

}
