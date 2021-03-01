<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Updater;

use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CategoryClosureTableUpdater implements CategoryClosureTableUpdaterInterface
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
    public function updateCategoryClosureTableParentEntriesForCategoryNode(NodeTransfer $nodeTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($nodeTransfer) {
            $this->executeUpdateCategoryClosureTableParentEntriesForCategoryNodeTransaction($nodeTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return void
     */
    protected function executeUpdateCategoryClosureTableParentEntriesForCategoryNodeTransaction(NodeTransfer $nodeTransfer): void
    {
        $this->categoryEntityManager->deleteCategoryClosureTableParentEntriesForCategoryNode($nodeTransfer->getIdCategoryNodeOrFail());
        $this->categoryEntityManager->createCategoryClosureTableParentEntriesForCategoryNode($nodeTransfer);
    }
}
