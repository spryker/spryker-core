<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Updater;

use ArrayObject;
use Generated\Shared\Transfer\CategoryUrlCollectionRequestTransfer;
use Generated\Shared\Transfer\CategoryUrlCollectionResponseTransfer;
use Spryker\Zed\Category\Business\Filter\CategoryUrlFilterInterface;
use Spryker\Zed\Category\Business\Validator\CategoryUrlValidatorInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CategoryUrlCollectionUpdater implements CategoryUrlCollectionUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Category\Business\Updater\CategoryUrlUpdaterInterface
     */
    protected CategoryUrlUpdaterInterface $categoryUrlUpdater;

    /**
     * @var \Spryker\Zed\Category\Business\Validator\CategoryUrlValidatorInterface
     */
    protected CategoryUrlValidatorInterface $categoryUrlValidator;

    /**
     * @var \Spryker\Zed\Category\Business\Filter\CategoryUrlFilterInterface
     */
    protected CategoryUrlFilterInterface $categoryUrlFilter;

    /**
     * @param \Spryker\Zed\Category\Business\Updater\CategoryUrlUpdaterInterface $categoryUrlUpdater
     * @param \Spryker\Zed\Category\Business\Validator\CategoryUrlValidatorInterface $categoryUrlValidator
     * @param \Spryker\Zed\Category\Business\Filter\CategoryUrlFilterInterface $categoryUrlFilter
     */
    public function __construct(
        CategoryUrlUpdaterInterface $categoryUrlUpdater,
        CategoryUrlValidatorInterface $categoryUrlValidator,
        CategoryUrlFilterInterface $categoryUrlFilter
    ) {
        $this->categoryUrlUpdater = $categoryUrlUpdater;
        $this->categoryUrlValidator = $categoryUrlValidator;
        $this->categoryUrlFilter = $categoryUrlFilter;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryUrlCollectionRequestTransfer $categoryUrlCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryUrlCollectionResponseTransfer
     */
    public function updateCategoryUrlCollection(
        CategoryUrlCollectionRequestTransfer $categoryUrlCollectionRequestTransfer
    ): CategoryUrlCollectionResponseTransfer {
        $this->assertCategoryUrlCollectionRequiredFields($categoryUrlCollectionRequestTransfer);

        $categoryUrlCollectionResponseTransfer = $this->categoryUrlValidator->validateCollection($categoryUrlCollectionRequestTransfer);
        if ($categoryUrlCollectionRequestTransfer->getIsTransactional() && $categoryUrlCollectionResponseTransfer->getErrors()->count()) {
            return $categoryUrlCollectionResponseTransfer;
        }

        [$validCategoryTransfers, $notValidCategoryTransfers] = $this->categoryUrlFilter->filterCategoriesByValidity($categoryUrlCollectionResponseTransfer);
        $this->getTransactionHandler()->handleTransaction(function () use ($validCategoryTransfers) {
            $this->executeUpdateCategoryUrlCollection($validCategoryTransfers);
        });

        return $categoryUrlCollectionResponseTransfer->setCategories(
            $this->categoryUrlFilter->mergeCategories($validCategoryTransfers, $notValidCategoryTransfers),
        );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\CategoryTransfer> $categoryTransfers
     *
     * @return void
     */
    protected function executeUpdateCategoryUrlCollection(ArrayObject $categoryTransfers): void
    {
        foreach ($categoryTransfers as $categoryTransfer) {
            $this->categoryUrlUpdater->updateCategoryUrl($categoryTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryUrlCollectionRequestTransfer $categoryUrlCollectionRequestTransfer
     *
     * @return void
     */
    protected function assertCategoryUrlCollectionRequiredFields(
        CategoryUrlCollectionRequestTransfer $categoryUrlCollectionRequestTransfer
    ): void {
        $categoryUrlCollectionRequestTransfer
            ->requireIsTransactional()
            ->requireCategories();

        foreach ($categoryUrlCollectionRequestTransfer->getCategories() as $categoryTransfer) {
            $categoryTransfer
                ->requireLocalizedAttributes()
                ->requireCategoryNode()
                ->getCategoryNodeOrFail()
                    ->requireIdCategoryNode();

            foreach ($categoryTransfer->getLocalizedAttributes() as $categoryLocalizedAttributeTransfer) {
                $categoryLocalizedAttributeTransfer
                    ->requireLocale()
                    ->getLocaleOrFail()
                        ->requireLocaleName();
            }
        }
    }
}
