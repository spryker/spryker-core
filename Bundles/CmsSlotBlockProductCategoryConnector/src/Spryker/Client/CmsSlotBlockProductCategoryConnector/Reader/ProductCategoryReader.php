<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockProductCategoryConnector\Reader;

use Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer;
use Spryker\Client\CmsSlotBlockProductCategoryConnector\Dependency\Client\CmsSlotBlockProductCategoryConnectorToLocaleClientInterface;
use Spryker\Client\CmsSlotBlockProductCategoryConnector\Dependency\Client\CmsSlotBlockProductCategoryConnectorToProductCategoryStorageClientInterface;
use Spryker\Client\CmsSlotBlockProductCategoryConnector\Dependency\Client\CmsSlotBlockProductCategoryConnectorToStoreClientInterface;

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
     * @var \Spryker\Client\CmsSlotBlockProductCategoryConnector\Dependency\Client\CmsSlotBlockProductCategoryConnectorToStoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \Spryker\Client\CmsSlotBlockProductCategoryConnector\Dependency\Client\CmsSlotBlockProductCategoryConnectorToLocaleClientInterface $localeClient
     * @param \Spryker\Client\CmsSlotBlockProductCategoryConnector\Dependency\Client\CmsSlotBlockProductCategoryConnectorToProductCategoryStorageClientInterface $productCategoryStorageClient
     * @param \Spryker\Client\CmsSlotBlockProductCategoryConnector\Dependency\Client\CmsSlotBlockProductCategoryConnectorToStoreClientInterface $storeClient
     */
    public function __construct(
        CmsSlotBlockProductCategoryConnectorToLocaleClientInterface $localeClient,
        CmsSlotBlockProductCategoryConnectorToProductCategoryStorageClientInterface $productCategoryStorageClient,
        CmsSlotBlockProductCategoryConnectorToStoreClientInterface $storeClient
    ) {
        $this->localeClient = $localeClient;
        $this->productCategoryStorageClient = $productCategoryStorageClient;
        $this->storeClient = $storeClient;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array<int>
     */
    public function getAbstractProductCategoryIds(int $idProductAbstract): array
    {
        $localeName = $this->localeClient->getCurrentLocale();
        $productAbstractCategoryStorageTransfer = $this->productCategoryStorageClient->findProductAbstractCategory(
            $idProductAbstract,
            $localeName,
            $this->storeClient->getCurrentStore()->getNameOrFail(),
        );

        if (!$productAbstractCategoryStorageTransfer) {
            return [];
        }

        return $this->getCategoryIds($productAbstractCategoryStorageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer $productAbstractCategoryStorageTransfer
     *
     * @return array<int>
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
