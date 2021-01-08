<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockProductCategoryConnector\Reader;

use Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer;
use Spryker\Client\CmsSlotBlockProductCategoryConnector\Dependency\Client\CmsSlotBlockProductCategoryConnectorToLocaleClientInterface;
use Spryker\Client\CmsSlotBlockProductCategoryConnector\Dependency\Client\CmsSlotBlockProductCategoryConnectorToProductCategoryStorageClientInterface;

class ProductCategoryReader implements ProductCategoryReaderInterface
{
    /**
     * @var \Spryker\Client\CmsSlotBlockProductCategoryConnector\Dependency\Client\CmsSlotBlockProductCategoryConnectorToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @var \Spryker\Client\CmsSlotBlockProductCategoryConnector\Dependency\Client\CmsSlotBlockProductCategoryConnectorToProductCategoryStorageClientInterface
     */
    protected $productCategoryStorageClient;

    /**
     * @param \Spryker\Client\CmsSlotBlockProductCategoryConnector\Dependency\Client\CmsSlotBlockProductCategoryConnectorToLocaleClientInterface $localeClient
     * @param \Spryker\Client\CmsSlotBlockProductCategoryConnector\Dependency\Client\CmsSlotBlockProductCategoryConnectorToProductCategoryStorageClientInterface $productCategoryStorageClient
     */
    public function __construct(
        CmsSlotBlockProductCategoryConnectorToLocaleClientInterface $localeClient,
        CmsSlotBlockProductCategoryConnectorToProductCategoryStorageClientInterface $productCategoryStorageClient
    ) {
        $this->localeClient = $localeClient;
        $this->productCategoryStorageClient = $productCategoryStorageClient;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function getAbstractProductCategoryIds(int $idProductAbstract): array
    {
        $localeName = $this->localeClient->getCurrentLocale();
        $productAbstractCategoryStorageTransfer = $this->productCategoryStorageClient->findProductAbstractCategory(
            $idProductAbstract,
            $localeName,
            getenv('APPLICATION_STORE') // is it acceptable?
        );

        if (!$productAbstractCategoryStorageTransfer) {
            return [];
        }

        return $this->getCategoryIds($productAbstractCategoryStorageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer $productAbstractCategoryStorageTransfer
     *
     * @return int[]
     */
    protected function getCategoryIds(
        ProductAbstractCategoryStorageTransfer $productAbstractCategoryStorageTransfer
    ): array {
        $productCategoryStorageTransfers = $productAbstractCategoryStorageTransfer->getCategories();
        $categoryIds = [];

        foreach ($productCategoryStorageTransfers as $productCategoryStorageTransfer) {
            $categoryIds[] = $productCategoryStorageTransfer->getCategoryId();
        }

        return $categoryIds;
    }
}
