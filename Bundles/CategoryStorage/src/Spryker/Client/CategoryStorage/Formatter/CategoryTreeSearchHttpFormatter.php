<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage\Formatter;

use ArrayObject;
use Generated\Shared\Transfer\CategoryNodeSearchResultTransfer;
use Generated\Shared\Transfer\SearchHttpResponseTransfer;
use Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToLocaleClientInterface;
use Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStoreClientInterface;
use Spryker\Client\CategoryStorage\Mapper\CategoryNodeStorageMapperInterface;
use Spryker\Client\CategoryStorage\Storage\CategoryTreeStorageReaderInterface;

class CategoryTreeSearchHttpFormatter implements CategoryTreeSearchHttpFormatterInterface
{
    /**
     * @var string
     */
    protected const FACET_NAME_CATEGORY = 'category';

    /**
     * @var \Spryker\Client\CategoryStorage\Storage\CategoryTreeStorageReaderInterface
     */
    protected CategoryTreeStorageReaderInterface $categoryTreeStorageReader;

    /**
     * @var \Spryker\Client\CategoryStorage\Mapper\CategoryNodeStorageMapperInterface
     */
    protected CategoryNodeStorageMapperInterface $categoryNodeStorageMapper;

    /**
     * @var \Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToLocaleClientInterface
     */
    protected CategoryStorageToLocaleClientInterface $localeClient;

    /**
     * @var \Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStoreClientInterface
     */
    protected CategoryStorageToStoreClientInterface $storeClient;

    /**
     * @param \Spryker\Client\CategoryStorage\Storage\CategoryTreeStorageReaderInterface $categoryTreeStorageReader
     * @param \Spryker\Client\CategoryStorage\Mapper\CategoryNodeStorageMapperInterface $categoryNodeStorageMapper
     * @param \Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToLocaleClientInterface $localeClient
     * @param \Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStoreClientInterface $storeClient
     */
    public function __construct(
        CategoryTreeStorageReaderInterface $categoryTreeStorageReader,
        CategoryNodeStorageMapperInterface $categoryNodeStorageMapper,
        CategoryStorageToLocaleClientInterface $localeClient,
        CategoryStorageToStoreClientInterface $storeClient
    ) {
        $this->categoryTreeStorageReader = $categoryTreeStorageReader;
        $this->categoryNodeStorageMapper = $categoryNodeStorageMapper;
        $this->localeClient = $localeClient;
        $this->storeClient = $storeClient;
    }

    /**
     * @param \Generated\Shared\Transfer\SearchHttpResponseTransfer $searchResult
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\CategoryNodeSearchResultTransfer>
     */
    public function format(SearchHttpResponseTransfer $searchResult): ArrayObject
    {
        $categoryNodeStorageTransfers = $this->categoryTreeStorageReader->getCategories(
            $this->localeClient->getCurrentLocale(),
            $this->storeClient->getCurrentStore()->getNameOrFail(),
        );

        $categoryNodeSearchResultTransfers = $this->categoryNodeStorageMapper->mapCategoryNodeStoragesToCategoryNodeSearchResults(
            $categoryNodeStorageTransfers,
            new ArrayObject(),
        );

        return $this->attachCategoryProductsCountToCategoryNodeSearchResultTransfers(
            $categoryNodeSearchResultTransfers,
            $searchResult->getFacets()[static::FACET_NAME_CATEGORY] ?? [],
        );
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\CategoryNodeSearchResultTransfer> $categoryNodeSearchResultTransfers
     * @param array<string, int> $categoryProductsCount
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\CategoryNodeSearchResultTransfer>
     */
    protected function attachCategoryProductsCountToCategoryNodeSearchResultTransfers(
        ArrayObject $categoryNodeSearchResultTransfers,
        array $categoryProductsCount
    ): ArrayObject {
        $categoryNodeSearchResultTransfers = $this->mergeCategoryNodeSearchResultWithCategoryAggregation(
            $categoryNodeSearchResultTransfers,
            $categoryProductsCount,
        );

        $this->summarizeChildrenCountForParentCategories(
            $categoryNodeSearchResultTransfers,
            $categoryProductsCount,
        );

        return $categoryNodeSearchResultTransfers;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\CategoryNodeSearchResultTransfer> $categoryNodeSearchResultTransfers
     * @param array<string, int> $categoryAggregation
     * @param \Generated\Shared\Transfer\CategoryNodeSearchResultTransfer|null $parentCategoryNodeSearchResultTransfer
     *
     * @return int
     */
    protected function summarizeChildrenCountForParentCategories(
        ArrayObject $categoryNodeSearchResultTransfers,
        array $categoryAggregation,
        ?CategoryNodeSearchResultTransfer $parentCategoryNodeSearchResultTransfer = null
    ): int {
        $totalCount = 0;

        foreach ($categoryNodeSearchResultTransfers as $categoryNodeSearchResultTransfer) {
            if ($categoryNodeSearchResultTransfer->getChildren()->count() > 0) {
                $totalCount += $this->summarizeChildrenCountForParentCategories(
                    $categoryNodeSearchResultTransfer->getChildren(),
                    $categoryAggregation,
                    $categoryNodeSearchResultTransfer,
                );
            } else {
                $totalCount += $categoryNodeSearchResultTransfer->getDocCount();
            }
        }

        if ($parentCategoryNodeSearchResultTransfer) {
            $parentCategoryNodeSearchResultTransfer->setDocCount(
                ($parentCategoryNodeSearchResultTransfer->getDocCount() + $totalCount),
            );
        }

        return $totalCount;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\CategoryNodeSearchResultTransfer> $categoryNodeSearchResultTransfers
     * @param array<string, int> $categoryAggregation
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\CategoryNodeSearchResultTransfer>
     */
    protected function mergeCategoryNodeSearchResultWithCategoryAggregation(
        ArrayObject $categoryNodeSearchResultTransfers,
        array $categoryAggregation
    ): ArrayObject {
        foreach ($categoryNodeSearchResultTransfers as $categoryNodeSearchResultTransfer) {
            $itemsCount = $categoryAggregation[$categoryNodeSearchResultTransfer->getName()] ?? 0;
            $categoryNodeSearchResultTransfer->setDocCount($itemsCount);

            if ($categoryNodeSearchResultTransfer->getChildren()->count()) {
                $categoryNodeSearchResultTransfer->setChildren(
                    $this->mergeCategoryNodeSearchResultWithCategoryAggregation(
                        $categoryNodeSearchResultTransfer->getChildren(),
                        $categoryAggregation,
                    ),
                );
            }
        }

        return $categoryNodeSearchResultTransfers;
    }
}
