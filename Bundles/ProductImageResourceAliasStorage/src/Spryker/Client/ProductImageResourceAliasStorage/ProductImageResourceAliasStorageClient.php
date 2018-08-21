<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductImageResourceAliasStorage;

use Generated\Shared\Transfer\ProductAbstractImageStorageTransfer;
use Generated\Shared\Transfer\ProductConcreteImageStorageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductImageResourceAliasStorage\ProductImageResourceAliasStorageFactory getFactory()
 */
class ProductImageResourceAliasStorageClient extends AbstractClient implements ProductImageResourceAliasStorageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractImageStorageTransfer|null
     */
    public function findProductImageAbstractStorageTransfer(string $sku, string $localeName): ?ProductAbstractImageStorageTransfer
    {
        return $this->getFactory()
            ->createProductAbstractImageStorageReader()
            ->findProductAbstractImageStorageData($sku, $localeName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductConcreteImageStorageTransfer|null
     */
    public function findProductImageConcreteStorageTransfer(string $sku, string $localeName): ?ProductConcreteImageStorageTransfer
    {
        return $this->getFactory()
            ->createProductConcreteImageStorageReader()
            ->findProductConcreteImageStorageData($sku, $localeName);
    }
}
