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

class ProductGroupExpander implements ProductGroupExpanderInterface
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
    public function extendProductGroup(ProductGroupTransfer $productGroupTransfer)
    {
        $this->assertProductGroupForExtension($productGroupTransfer);

        return $this->handleDatabaseTransaction(function () use ($productGroupTransfer) {
            return $this->executeExtendProductGroupTransaction($productGroupTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return void
     */
    protected function assertProductGroupForExtension(ProductGroupTransfer $productGroupTransfer)
    {
        $productGroupTransfer->requireIdProductGroup();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ProductGroupTransfer
     */
    protected function executeExtendProductGroupTransaction(ProductGroupTransfer $productGroupTransfer)
    {
        $productGroupEntity = $this->productGroupEntityReader->findProductGroupEntity($productGroupTransfer);

        $this->touchProductGroup($productGroupTransfer);

        return $this->saveProductGroupEntity($productGroupEntity, $productGroupTransfer);
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

    /**
     * @param \Orm\Zed\ProductGroup\Persistence\SpyProductGroup $productGroupEntity
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ProductGroupTransfer
     */
    protected function saveProductGroupEntity(SpyProductGroup $productGroupEntity, ProductGroupTransfer $productGroupTransfer)
    {
        $idProductAbstracts = $productGroupTransfer->getIdProductAbstracts();
        $existingProductAbstractGroups = $this->getExistingIdProductAbstracts($productGroupEntity);
        $productGroupTransfer->setIdProductAbstracts($existingProductAbstractGroups);

        $position = count($existingProductAbstractGroups);
        foreach ($idProductAbstracts as $idProductAbstract) {
            if (in_array($idProductAbstract, $existingProductAbstractGroups)) {
                continue;
            }

            $productAbstractGroupEntity = $this->createProductAbstractGroupEntity($idProductAbstract, $position);
            $productGroupEntity->addSpyProductAbstractGroup($productAbstractGroupEntity);
            $productGroupTransfer->addIdProductAbstract($idProductAbstract);
            $position++;
        }

        $productGroupEntity->save();

        return $productGroupTransfer;
    }

    /**
     * @param \Orm\Zed\ProductGroup\Persistence\SpyProductGroup $productGroupEntity
     *
     * @return array
     */
    protected function getExistingIdProductAbstracts(SpyProductGroup $productGroupEntity)
    {
        $existingProductAbstractGroups = [];

        foreach ($productGroupEntity->getSpyProductAbstractGroups() as $productAbstractGroupEntity) {
            $existingProductAbstractGroups[] = $productAbstractGroupEntity->getFkProductAbstract();
        }

        return $existingProductAbstractGroups;
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
}
