<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Creator;

use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface;

class CategoryClosureTableCreator implements CategoryClosureTableCreatorInterface
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
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return void
     */
    public function createCategoryClosureTable(NodeTransfer $nodeTransfer): void
    {
        if (!$nodeTransfer->getFkParentCategoryNode()) {
            $this->categoryEntityManager->createCategoryClosureTableRootNode($nodeTransfer);

            return;
        }

        $this->categoryEntityManager->createCategoryClosureTableNodes($nodeTransfer);
    }
}
