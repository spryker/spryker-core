<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\ProductStorage\Persistence;

/**
 * @method \Spryker\Zed\ProductStorage\Persistence\ProductStoragePersistenceFactory getFactory()
 */
interface ProductStorageRepositoryInterface
{
    /**
     * @param array<int> $productAbstractIds
     *
     * @return array<mixed>
     */
    public function getProductAbstractsByIds(array $productAbstractIds): array;

    /**
     * @param array<int> $idProductAbstract
     *
     * @return array<array<string, int>>
     */
    public function getProductConcretesCountByIdProductAbstracts(array $idProductAbstract): array;

    /**
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\SitemapUrlTransfer>
     */
    public function getSitemapUrls(string $storeName): array;
}
