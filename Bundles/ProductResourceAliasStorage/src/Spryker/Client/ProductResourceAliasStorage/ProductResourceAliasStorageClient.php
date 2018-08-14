<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductResourceAliasStorage;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductResourceAliasStorage\ProductResourceAliasStorageFactory getFactory()
 */
class ProductResourceAliasStorageClient extends AbstractClient implements ProductResourceAliasStorageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param string $localeName
     *
     * @return array|null
     */
    public function findProductAbstractStorageData(string $sku, string $localeName): ?array
    {
        return $this->getFactory()
            ->createProductAbstractStorageReader()
            ->findProductAbstractStorageData($sku, $localeName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param string $localeName
     *
     * @return array|null
     */
    public function getProductConcreteStorageData(string $sku, string $localeName): ?array
    {
        return $this->getFactory()
            ->createProductConcreteStorageReader()
            ->findProductConcreteStorageData($sku, $localeName);
    }
}
