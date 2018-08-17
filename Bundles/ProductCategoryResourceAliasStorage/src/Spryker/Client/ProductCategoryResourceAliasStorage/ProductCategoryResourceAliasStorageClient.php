<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCategoryResourceAliasStorage;

use Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductCategoryResourceAliasStorage\ProductCategoryResourceAliasStorageFactory getFactory()
 */
class ProductCategoryResourceAliasStorageClient extends AbstractClient implements ProductCategoryResourceAliasStorageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer|null
     */
    public function findProductCategoryAbstractStorageTransfer(string $sku, string $localeName): ?ProductAbstractCategoryStorageTransfer
    {
        return $this->getFactory()
            ->createProductAbstractCategoryStorageReader()
            ->findProductAbstractCategoryStorageData($sku, $localeName);
    }
}
