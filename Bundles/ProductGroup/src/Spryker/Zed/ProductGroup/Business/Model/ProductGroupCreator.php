<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroup\Business\Model;

use Generated\Shared\Transfer\ProductGroupTransfer;
use Orm\Zed\ProductGroup\Persistence\SpyProductAbstractGroup;
use Orm\Zed\ProductGroup\Persistence\SpyProductGroup;

class ProductGroupCreator implements ProductGroupCreatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ProductGroupTransfer
     */
    public function createProductGroup(ProductGroupTransfer $productGroupTransfer)
    {
        $this->assertProductGroupForCreate($productGroupTransfer);

        if (!$productGroupTransfer->getIdProductAbstracts()) {
            return $productGroupTransfer;
        }

        $productGroupEntity = $this->createProductGroupEntity($productGroupTransfer);
        $productGroupTransfer->setIdProductGroup($productGroupEntity->getIdProductGroup());

        return $productGroupTransfer;
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

}
