<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage\Formatter;

use ArrayObject;
use Generated\Shared\Transfer\CategorySearchResultTransfer;
use Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer;
use Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToLocaleClientInterface;
use Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToStoreClientInterface;
use Spryker\Client\CategoryStorage\Mapper\CategoryNodeStorageMapperInterface;
use Spryker\Client\CategoryStorage\Storage\CategoryTreeStorageReaderInterface;

class CategorySuggestionsSearchHttpFormatter implements CategorySuggestionsSearchHttpFormatterInterface
{
    /**
     * @var string
     */
    protected const TYPE_CATEGORY = 'category';

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
     * @param \Generated\Shared\Transfer\SuggestionsSearchHttpResponseTransfer $suggestionsSearchHttpResponseTransfer
     *
     * @return array<int, mixed>
     */
    public function format(SuggestionsSearchHttpResponseTransfer $suggestionsSearchHttpResponseTransfer): array
    {
        $categoryNames = $suggestionsSearchHttpResponseTransfer->getCategories();

        $categoryNodeStorageTransfers = $this->categoryTreeStorageReader->getCategories(
            $this->localeClient->getCurrentLocale(),
            $this->storeClient->getCurrentStore()->getNameOrFail(),
        );

        $categoryNodeSearchResultTransfers = $this->categoryNodeStorageMapper->mapCategoryNodeStoragesToCategoryNodeSearchResults(
            $categoryNodeStorageTransfers,
            new ArrayObject(),
        );

        return $this->getFilteredCategories($categoryNodeSearchResultTransfers, $categoryNames);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\CategoryNodeSearchResultTransfer> $categoryNodeSearchResultTransfers
     * @param array $categoryNames
     *
     * @return array<int, mixed>
     */
    protected function getFilteredCategories(
        ArrayObject $categoryNodeSearchResultTransfers,
        array $categoryNames
    ): array {
        $categories = [];
        $categorySearchResultTransfer = (new CategorySearchResultTransfer())
            ->setType(static::TYPE_CATEGORY);
        foreach ($categoryNodeSearchResultTransfers as $categoryNodeSearchResultTransfer) {
            if ($categoryNodeSearchResultTransfer->getChildren()->count() > 0) {
                $categories = array_merge($categories, $this->getFilteredCategories(
                    $categoryNodeSearchResultTransfer->getChildren(),
                    $categoryNames,
                ));
            }
            if (!in_array($categoryNodeSearchResultTransfer->getName(), $categoryNames)) {
                continue;
            }
            $categorySearchResultTransfer->fromArray($categoryNodeSearchResultTransfer->toArray(), true);
            $categories[] = $categorySearchResultTransfer->toArray();
        }

        return $categories;
    }
}
