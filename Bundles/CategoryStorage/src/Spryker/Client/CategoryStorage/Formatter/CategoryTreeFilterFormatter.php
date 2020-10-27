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
     * @uses \Spryker\Client\SearchElasticsearch\AggregationExtractor\CategoryExtractor::DOC_COUNT
     */
    protected const DOC_COUNT = 'doc_count';

    /**
     * @uses \Spryker\Client\SearchElasticsearch\AggregationExtractor\CategoryExtractor::KEY_BUCKETS
     */
    protected const KEY_BUCKETS = 'buckets';

    /**
     * @uses \Spryker\Client\SearchElasticsearch\AggregationExtractor\CategoryExtractor::KEY_KEY
     */
    protected const KEY_KEY = 'key';

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
    public function formatCategoryTreeFilter(ResultSet $searchResult): ArrayObject
    {
        $categoryDocCounts = $this->getMappedCategoryDocCountsByNodeId($searchResult);

        $categoryNodeStorageTransfers = $this->categoryTreeStorageReader->getCategories(
            $this->localeClient->getCurrentLocale()
        );

        $categoryNodeSearchResultTransfers = $this->mapCategoryNodeStoragesToCategoryNodeSearchResults(
            $categoryNodeStorageTransfers,
            new ArrayObject()
        );

        return $this->mergeCategoryNodeSearchResultWithCategoryDocCount(
            $categoryNodeSearchResultTransfers,
            $categoryDocCounts
        );
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CategoryNodeStorageTransfer[] $categoryNodeStorageTransfers
     * @param \ArrayObject|\Generated\Shared\Transfer\CategoryNodeSearchResultTransfer[] $categoryNodeSearchResultTransfers
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CategoryNodeSearchResultTransfer[]
     */
    protected function mapCategoryNodeStoragesToCategoryNodeSearchResults(
        ArrayObject $categoryNodeStorageTransfers,
        ArrayObject $categoryNodeSearchResultTransfers
    ): ArrayObject {
        foreach ($categoryNodeStorageTransfers as $categoryNodeStorageTransfer) {
            $categoryNodeSearchResultTransfers->append(
                (new CategoryNodeSearchResultTransfer())->fromArray($categoryNodeStorageTransfer->toArray(), true)
            );
        }

        return $categoryNodeSearchResultTransfers;
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
     * @return int[]
     */
    protected function getMappedCategoryDocCountsByNodeId(ResultSet $searchResult): array
    {
        $categoryDocCounts = [];
        $name = $this->categoryStorageConfig->getCategoryFacetAggregationName();
        $docCountAggregation = $searchResult->getAggregations()[$name] ?? [];

        if (!$docCountAggregation) {
            return $categoryDocCounts;
        }

        $categoryBuckets = $docCountAggregation[static::KEY_BUCKETS] ?? [];

        foreach ($categoryBuckets as $categoryBucket) {
            $key = $categoryBucket[static::KEY_KEY] ?? null;
            $docCount = $categoryBucket[static::DOC_COUNT] ?? null;

            if ($key && $docCount) {
                $categoryDocCounts[$key] = $docCount;
            }
        }

        return $categoryDocCounts;
    }
}
