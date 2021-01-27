<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryStorage;

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
     * @param int $idProductAbstract
     * @param string $locale
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer|null
     */
    public function findProductAbstractCategory($idProductAbstract, $locale, string $storeName)
    {
        return $this->getFactory()
            ->createProductCategoryStorageReader()
            ->findProductAbstractCategory($idProductAbstract, $locale, $storeName);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productAbstractIds
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer[]
     */
    public function findBulkProductAbstractCategory(array $productAbstractIds, string $localeName, string $storeName): array
    {
        return $this->getFactory()
            ->createProductCategoryStorageReader()
            ->findBulkProductAbstractCategory($productAbstractIds, $localeName, $storeName);
    }
}
