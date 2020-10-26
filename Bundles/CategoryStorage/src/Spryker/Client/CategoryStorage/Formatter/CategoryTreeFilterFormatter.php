<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage\Formatter;

use ArrayObject;
use Elastica\ResultSet;
use Generated\Shared\Transfer\CategoryNodeSearchResultTransfer;
use Spryker\Client\CategoryStorage\CategoryStorageConfig;
use Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToLocaleClientInterface;
use Spryker\Client\CategoryStorage\Storage\CategoryTreeStorageReaderInterface;

class CategoryTreeFilterFormatter implements CategoryTreeFilterFormatterInterface
{
    /**
     * @var \Spryker\Client\CategoryStorage\CategoryStorageConfig
     */
    protected $categoryStorageConfig;

    /**
     * @var \Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @var \Spryker\Client\CategoryStorage\Storage\CategoryTreeStorageReaderInterface
     */
    protected $categoryTreeStorageReader;

    /**
     * @param \Spryker\Client\CategoryStorage\CategoryStorageConfig $categoryStorageConfig
     * @param \Spryker\Client\CategoryStorage\Dependency\Client\CategoryStorageToLocaleClientInterface $localeClient
     * @param \Spryker\Client\CategoryStorage\Storage\CategoryTreeStorageReaderInterface $categoryTreeStorageReader
     */
    public function __construct(
        CategoryStorageConfig $categoryStorageConfig,
        CategoryStorageToLocaleClientInterface $localeClient,
        CategoryTreeStorageReaderInterface $categoryTreeStorageReader
    ) {
        $this->categoryStorageConfig = $categoryStorageConfig;
        $this->localeClient = $localeClient;
        $this->categoryTreeStorageReader = $categoryTreeStorageReader;
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CategoryNodeSearchResultTransfer[]
     */
    public function formatResultSetToCategoryTreeFilter(ResultSet $searchResult): ArrayObject
    {
        $categoryDocCounts = $this->getMappedCategoryDocCountsByNodeId($searchResult);

        $categoryNodeStorageTransfers = $this->categoryTreeStorageReader->getCategories(
            $this->localeClient->getCurrentLocale()
        );

        return $this->formatCategoryTreeFilter($categoryNodeStorageTransfers, $categoryDocCounts);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CategoryNodeStorageTransfer[] $categoryNodeStorageTransfers
     * @param array $categoryDocCounts
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CategoryNodeSearchResultTransfer[]
     */
    protected function formatCategoryTreeFilter(
        ArrayObject $categoryNodeStorageTransfers,
        array $categoryDocCounts
    ): ArrayObject {
        $categoryNodeSearchResultTransfers = new ArrayObject();

        foreach ($categoryNodeStorageTransfers as $categoryNodeStorageTransfer) {
            $categoryNodeSearchResultTransfers->append(
                (new CategoryNodeSearchResultTransfer())->fromArray($categoryNodeStorageTransfer->toArray(), true)
            );
        }

        return $this->mergeCategoryNodeSearchResultWithCategoryDocCount(
            $categoryNodeSearchResultTransfers,
            $categoryDocCounts
        );
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CategoryNodeSearchResultTransfer[] $categoryNodeSearchResultTransfers
     * @param array $categoryDocCounts
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CategoryNodeSearchResultTransfer[]
     */
    protected function mergeCategoryNodeSearchResultWithCategoryDocCount(
        ArrayObject $categoryNodeSearchResultTransfers,
        array $categoryDocCounts
    ): ArrayObject {
        foreach ($categoryNodeSearchResultTransfers as $categoryNodeSearchResultTransfer) {
            $docCount = $categoryDocCounts[$categoryNodeSearchResultTransfer->getNodeId()] ?? 0;
            $categoryNodeSearchResultTransfer->setDocCount($docCount);

            if ($categoryNodeSearchResultTransfer->getChildren()->count()) {
                $categoryNodeSearchResultTransfer->setChildren(
                    $this->mergeCategoryNodeSearchResultWithCategoryDocCount(
                        $categoryNodeSearchResultTransfer->getChildren(),
                        $categoryDocCounts
                    )
                );
            }

            if ($categoryNodeSearchResultTransfer->getParents()->count()) {
                $categoryNodeSearchResultTransfer->setParents(
                    $this->mergeCategoryNodeSearchResultWithCategoryDocCount(
                        $categoryNodeSearchResultTransfer->getParents(),
                        $categoryDocCounts
                    )
                );
            }
        }

        return $categoryNodeSearchResultTransfers;
    }

    /**
     * @param \Elastica\ResultSet $searchResult
     *
     * @return array
     */
    protected function getMappedCategoryDocCountsByNodeId(ResultSet $searchResult): array
    {
        $categoryDocCounts = [];
        $name = $this->categoryStorageConfig->getCategoryDocCountAggregationName();
        $docCountAggregation = $searchResult->getAggregations()[$name] ?? [];

        if (!$docCountAggregation) {
            return $categoryDocCounts;
        }

        $categoryBuckets = $docCountAggregation['buckets'] ?? [];

        foreach ($categoryBuckets as $categoryBucket) {
            $key = $categoryBucket['key'] ?? null;
            $docCount = $categoryBucket['doc_count'] ?? null;

            if ($key && $docCount) {
                $categoryDocCounts[$key] = $docCount;
            }
        }

        return $categoryDocCounts;
    }
}
