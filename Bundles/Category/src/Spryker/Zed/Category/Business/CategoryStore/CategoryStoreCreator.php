<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\CategoryStore;

use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface;

class CategoryStoreCreator implements CategoryStoreCreatorInterface
{
    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface
     */
    protected $categoryEntityManager;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface $categoryEntityManager
     */
    public function __construct(CategoryEntityManagerInterface $categoryEntityManager)
    {
        $this->categoryEntityManager = $categoryEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function createCategoryStoreRelations(CategoryTransfer $categoryTransfer): void
    {
        if (!$categoryTransfer->getStoreRelation()) {
            return;
        }

        $this->categoryEntityManager->createCategoryStoreRelationForStores(
            $categoryTransfer->getIdCategoryOrFail(),
            $categoryTransfer->getStoreRelation()->getIdStores()
        );
    }
}
