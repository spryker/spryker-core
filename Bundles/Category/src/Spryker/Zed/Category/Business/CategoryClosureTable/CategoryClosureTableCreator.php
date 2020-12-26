<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\CategoryClosureTable;

use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CategoryClosureTableCreator implements CategoryClosureTableCreatorInterface
{
    use TransactionTrait;

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
        $this->getTransactionHandler()->handleTransaction(function () use ($nodeTransfer) {
            $this->executeCreateCategoryClosureTableTransaction($nodeTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return void
     */
    protected function executeCreateCategoryClosureTableTransaction(NodeTransfer $nodeTransfer): void
    {
        if (!$nodeTransfer->getFkParentCategoryNode()) {
            $this->categoryEntityManager->createCategoryClosureTableRootNode($nodeTransfer);

            return;
        }

        $this->categoryEntityManager->createCategoryClosureTableNodes($nodeTransfer);
    }
}
