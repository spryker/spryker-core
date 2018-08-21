<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi\Dependency\Client;

use Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer;

class CategoriesRestApiToProductCategoryResourceAliasStorageClientBridge implements CategoriesRestApiToProductCategoryResourceAliasStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductCategoryResourceAliasStorage\ProductCategoryResourceAliasStorageClientInterface
     */
    protected $productCategoryResourceAliasStorageClient;

    /**
     * @param \Spryker\Client\ProductCategoryResourceAliasStorage\ProductCategoryResourceAliasStorageClientInterface $productCategoryResourceAliasStorageClient
     */
    public function __construct($productCategoryResourceAliasStorageClient)
    {
        $this->productCategoryResourceAliasStorageClient = $productCategoryResourceAliasStorageClient;
    }

    /**
     * @param string $sku
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer|null
     */
    public function findProductCategoryAbstractStorageTransfer(string $sku, string $localeName): ?ProductAbstractCategoryStorageTransfer
    {
        return $this->productCategoryResourceAliasStorageClient->findProductCategoryAbstractStorageTransfer($locale);
    }
}
