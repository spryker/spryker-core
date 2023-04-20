<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryStorage;

use Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductCategoryStorage\ProductCategoryStorageFactory getFactory()
 */
class ProductCategoryStorageClient extends AbstractClient implements ProductCategoryStorageClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Client\ProductCategoryStorage\ProductCategoryStorageClient::findBulkProductAbstractCategory()} instead.
     *
     * @param int $idProductAbstract
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer|null
     */
    public function findProductAbstractCategory(
        int $idProductAbstract,
        string $localeName,
        string $storeName
    ): ?ProductAbstractCategoryStorageTransfer {
        return $this->getFactory()
            ->createProductCategoryStorageReader()
            ->findProductAbstractCategory($idProductAbstract, $localeName, $storeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productAbstractIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer>
     */
    public function findBulkProductAbstractCategory(array $productAbstractIds, string $localeName, string $storeName): array
    {
        return $this->getFactory()
            ->createProductCategoryStorageReader()
            ->findBulkProductAbstractCategory($productAbstractIds, $localeName, $storeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int, \Generated\Shared\Transfer\ProductCategoryStorageTransfer> $productCategoryStorageTransfers
     * @param string $httpReferer
     *
     * @return list<\Generated\Shared\Transfer\ProductCategoryStorageTransfer>
     */
    public function filterProductCategoriesByHttpReferer(array $productCategoryStorageTransfers, string $httpReferer): array
    {
        return $this->getFactory()
            ->createProductCategoryStorageFilter()
            ->filterProductCategoriesByHttpReferer($productCategoryStorageTransfers, $httpReferer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int, \Generated\Shared\Transfer\ProductCategoryStorageTransfer> $productCategoryStorageTransfers
     *
     * @return list<\Generated\Shared\Transfer\ProductCategoryStorageTransfer>
     */
    public function sortProductCategories(array $productCategoryStorageTransfers): array
    {
        return $this->getFactory()
            ->createProductCategoryStorageSorter()
            ->sortProductCategories($productCategoryStorageTransfers);
    }
}
