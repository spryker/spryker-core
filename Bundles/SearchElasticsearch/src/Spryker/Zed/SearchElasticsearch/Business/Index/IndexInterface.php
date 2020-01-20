<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SearchElasticsearch\Business\Index;

use Generated\Shared\Transfer\ElasticsearchSearchContextTransfer;
use Generated\Shared\Transfer\SearchContextTransfer;

interface IndexInterface
{
    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return bool
     */
    public function openIndex(SearchContextTransfer $searchContextTransfer): bool;

    /**
     * @return bool
     */
    public function openIndexes(): bool;

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return bool
     */
    public function closeIndex(SearchContextTransfer $searchContextTransfer): bool;

    /**
     * @return bool
     */
    public function closeIndexes(): bool;

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $searchContextTransfer
     *
     * @return bool
     */
    public function deleteIndex(SearchContextTransfer $searchContextTransfer): bool;

    /**
     * @return bool
     */
    public function deleteIndexes(): bool;

    /**
     * @param \Generated\Shared\Transfer\SearchContextTransfer $sourceSearchContextTransfer
     * @param \Generated\Shared\Transfer\SearchContextTransfer $targetSearchContextTransfer
     *
     * @return bool
     */
    public function copyIndex(SearchContextTransfer $sourceSearchContextTransfer, SearchContextTransfer $targetSearchContextTransfer): bool;

    /**
     * @param \Generated\Shared\Transfer\ElasticsearchSearchContextTransfer $elasticsearchSearchContextTransfer
     *
     * @return int
     */
    public function getDocumentsTotalCount(ElasticsearchSearchContextTransfer $elasticsearchSearchContextTransfer): int;

    /**
     * @param \Generated\Shared\Transfer\ElasticsearchSearchContextTransfer $elasticsearchSearchContextTransfer
     *
     * @return array
     */
    public function getIndexMetaData(ElasticsearchSearchContextTransfer $elasticsearchSearchContextTransfer): array;

    /**
     * @return string[]
     */
    public function getIndexNames(): array;
}
