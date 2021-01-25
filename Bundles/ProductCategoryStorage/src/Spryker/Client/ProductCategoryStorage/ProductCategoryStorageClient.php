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
     * @param string|null $storeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer|null
     */
    public function findProductAbstractCategory($idProductAbstract, $locale, ?string $storeName = null)
    {
        if ($storeName === null) {
            trigger_error('Pass the $storeName parameter for the forward compatibility with next major version.', E_USER_DEPRECATED);
        }

        return $this->getFactory()
            ->createProductCategoryStorageReader()
            ->findProductAbstractCategory($idProductAbstract, $locale);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productAbstractIds
     * @param string $localeName
     * @param string|null $storeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer[]
     */
    public function findBulkProductAbstractCategory(array $productAbstractIds, string $localeName, ?string $storeName = null): array
    {
        if ($storeName === null) {
            trigger_error('Pass the $storeName parameter for the forward compatibility with next major version.', E_USER_DEPRECATED);
        }

        return $this->getFactory()
            ->createProductCategoryStorageReader()
            ->findBulkProductAbstractCategory($productAbstractIds, $localeName);
    }
}
