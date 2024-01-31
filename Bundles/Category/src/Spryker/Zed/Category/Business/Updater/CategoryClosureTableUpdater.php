<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Updater;

use ArrayObject;
use Generated\Shared\Transfer\CategoryClosureTableCollectionRequestTransfer;
use Generated\Shared\Transfer\CategoryClosureTableCollectionResponseTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Zed\Category\Business\Filter\CategoryClosureTableFilterInterface;
use Spryker\Zed\Category\Business\Validator\CategoryClosureTableValidatorInterface;
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
     * @var \Spryker\Zed\Category\Business\Validator\CategoryClosureTableValidatorInterface
     */
    protected CategoryClosureTableValidatorInterface $categoryClosureTableValidator;

    /**
     * @var \Spryker\Zed\Category\Business\Filter\CategoryClosureTableFilterInterface
     */
    protected CategoryClosureTableFilterInterface $categoryClosureTableFilter;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryEntityManagerInterface $categoryEntityManager
     * @param \Spryker\Zed\Category\Business\Validator\CategoryClosureTableValidatorInterface $categoryClosureTableValidator
     * @param \Spryker\Zed\Category\Business\Filter\CategoryClosureTableFilterInterface $categoryClosureTableFilter
     */
    public function __construct(
        CategoryEntityManagerInterface $categoryEntityManager,
        CategoryClosureTableValidatorInterface $categoryClosureTableValidator,
        CategoryClosureTableFilterInterface $categoryClosureTableFilter
    ) {
        $this->categoryEntityManager = $categoryEntityManager;
        $this->categoryClosureTableValidator = $categoryClosureTableValidator;
        $this->categoryClosureTableFilter = $categoryClosureTableFilter;
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
     * @param \Generated\Shared\Transfer\CategoryClosureTableCollectionRequestTransfer $categoryClosureTableCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryClosureTableCollectionResponseTransfer
     */
    public function updateCategoryClosureTableCollection(
        CategoryClosureTableCollectionRequestTransfer $categoryClosureTableCollectionRequestTransfer
    ): CategoryClosureTableCollectionResponseTransfer {
        $this->assertCategoryClosureTableCollectionRequiredFields(
            $categoryClosureTableCollectionRequestTransfer,
        );

        $categoryClosureTableCollectionResponseTransfer = $this->categoryClosureTableValidator->validateCollection(
            $categoryClosureTableCollectionRequestTransfer,
        );
        if ($categoryClosureTableCollectionRequestTransfer->getIsTransactional() && $categoryClosureTableCollectionResponseTransfer->getErrors()->count()) {
            return $categoryClosureTableCollectionResponseTransfer;
        }

        [$validNodeTransfers, $notValidNodeTransfers] = $this->categoryClosureTableFilter->filterCategoryNodesByValidity(
            $categoryClosureTableCollectionResponseTransfer,
        );
        $this->getTransactionHandler()->handleTransaction(function () use ($validNodeTransfers) {
            $this->executeUpdateCategoryClosureTableCollection($validNodeTransfers);
        });

        return $categoryClosureTableCollectionResponseTransfer->setCategoryNodes(
            $this->categoryClosureTableFilter->mergeCategoryNodes($validNodeTransfers, $notValidNodeTransfers),
        );
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

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\NodeTransfer> $nodeTransfers
     *
     * @return void
     */
    protected function executeUpdateCategoryClosureTableCollection(ArrayObject $nodeTransfers): void
    {
        foreach ($nodeTransfers as $nodeTransfer) {
            $this->executeUpdateCategoryClosureTableParentEntriesForCategoryNodeTransaction($nodeTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryClosureTableCollectionRequestTransfer $categoryClosureTableCollectionRequestTransfer
     *
     * @return void
     */
    protected function assertCategoryClosureTableCollectionRequiredFields(
        CategoryClosureTableCollectionRequestTransfer $categoryClosureTableCollectionRequestTransfer
    ): void {
        $categoryClosureTableCollectionRequestTransfer
            ->requireIsTransactional()
            ->requireCategoryNodes();

        foreach ($categoryClosureTableCollectionRequestTransfer->getCategoryNodes() as $nodeTransfer) {
            $nodeTransfer->requireIdCategoryNode();
        }
    }
}
