<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CategoryStorage\Formatter;

use ArrayObject;
use Generated\Shared\Transfer\CategoryNodeSearchResultTransfer;
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
     * @var \Spryker\Client\CategoryStorage\Storage\CategoryTreeStorageReaderInterface
     */
    protected $categoryTreeStorageReader;

    /**
     * @param \Spryker\Client\CategoryStorage\Storage\CategoryTreeStorageReaderInterface $categoryTreeStorageReader
     */
    public function __construct(CategoryTreeStorageReaderInterface $categoryTreeStorageReader)
    {
        $this->categoryTreeStorageReader = $categoryTreeStorageReader;
    }

    /**
     * @param array $docCountAggregation
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeSearchResultTransfer[]|\ArrayObject
     */
    public function formatCategoryTreeFilter(array $docCountAggregation, string $localeName, string $storeName): ArrayObject
    {
        $categoryDocCounts = $this->getMappedCategoryDocCountsByNodeId($docCountAggregation);

        $categoryNodeStorageTransfers = $this->categoryTreeStorageReader->getCategories($localeName, $storeName);
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
     * @param array $docCountAggregation
     *
     * @return int[]
     */
    protected function getMappedCategoryDocCountsByNodeId(array $docCountAggregation): array
    {
        $categoryDocCounts = [];
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
