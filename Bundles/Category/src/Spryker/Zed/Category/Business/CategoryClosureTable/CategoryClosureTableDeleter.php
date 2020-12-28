<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\CategoryClosureTable;

use Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CategoryClosureTableDeleter implements CategoryClosureTableDeleterInterface
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
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function deleteCategoryClosureTable(int $idCategoryNode): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($idCategoryNode) {
            $this->executeDeleteCategoryClosureTableTransaction($idCategoryNode);
        });
    }

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    protected function executeDeleteCategoryClosureTableTransaction(int $idCategoryNode): void
    {
        $this->categoryEntityManager->deleteCategoryClosureTable($idCategoryNode);
    }
}
