<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Persistence;

use Generator;

interface CategoryStorageRepositoryInterface
{
    /**
     * @param int $offset
     * @param int $limit
     * @param array<int> $categoryNodeIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getCategoryNodeStorageSynchronizationDataTransfersByCategoryNodeIds(int $offset, int $limit, array $categoryNodeIds): array;

    /**
     * @param int $offset
     * @param int $limit
     * @param array<int> $categoryTreeStorageIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getCategoryTreeStorageSynchronizationDataTransfersByCategoryTreeStorageIds(int $offset, int $limit, array $categoryTreeStorageIds): array;

    /**
     * @deprecated Use {@link \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageRepositoryInterface::getSitemapGeneratorUrls()} instead.
     *
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\SitemapUrlTransfer>
     */
    public function getSitemapUrls(string $storeName): array;

    /**
     * @param string $storeName
     * @param int $limit
     *
     * @return \Generator
     */
    public function getSitemapGeneratorUrls(string $storeName, int $limit): Generator;
}
