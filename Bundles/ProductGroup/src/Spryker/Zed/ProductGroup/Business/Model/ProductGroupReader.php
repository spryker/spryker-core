<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroup\Business\Model;

use Generated\Shared\Transfer\ProductGroupTransfer;
use Spryker\Zed\ProductGroup\Persistence\ProductGroupQueryContainerInterface;

class ProductGroupReader implements ProductGroupReaderInterface
{
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
    public function findProductGroup(ProductGroupTransfer $productGroupTransfer)
    {
        $this->assertProductGroupForRead($productGroupTransfer);

        $productAbstractGroupEntities = $this->productGroupQueryContainer
            ->queryProductAbstractGroupsById($productGroupTransfer->getIdProductGroup())
            ->find();

        if (!$productAbstractGroupEntities->count()) {
            return null;
        }

        $idProductAbstracts = [];
        foreach ($productAbstractGroupEntities as $productAbstractGroupEntity) {
            $idProductAbstracts[] = $productAbstractGroupEntity->getFkProductAbstract();
        }

        $productGroupTransfer->setIdProductAbstracts($idProductAbstracts);

        return $productGroupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductGroupTransfer $productGroupTransfer
     *
     * @return void
     */
    protected function assertProductGroupForRead(ProductGroupTransfer $productGroupTransfer)
    {
        $productGroupTransfer->requireIdProductGroup();
    }
}
