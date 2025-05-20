<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStorage\Persistence;

use Generated\Shared\Transfer\MerchantStorageCriteriaTransfer;
use Generator;
use Propel\Runtime\Collection\ObjectCollection;

interface MerchantStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantStorageCriteriaTransfer $merchantStorageCriteriaTransfer
     *
     * @return \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\MerchantStorage\Persistence\SpyMerchantStorage>
     */
    public function getFilteredMerchantStorageEntityTransfers(
        MerchantStorageCriteriaTransfer $merchantStorageCriteriaTransfer
    ): ObjectCollection;

    /**
     * @deprecated Use {@link \Spryker\Zed\MerchantStorage\Persistence\MerchantStorageRepositoryInterface::getSitemapGeneratorUrls()} instead.
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
