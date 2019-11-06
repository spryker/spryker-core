<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsSlotBlockProductCategoryConnector\Resolver;

use Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer;
use Spryker\Client\CmsSlotBlockProductCategoryConnector\Dependency\Client\CmsSlotBlockProductCategoryConnectorToLocaleClientInterface;
use Spryker\Client\CmsSlotBlockProductCategoryConnector\Dependency\Client\CmsSlotBlockProductCategoryConnectorToProductCategoryStorageClientInterface;

class ProductCategoryCmsSlotBlockConditionResolver implements ProductCategoryCmsSlotBlockConditionResolverInterface
{
    protected const CONDITIONS_DATA_KEY_ALL = 'all';
    protected const CONDITIONS_DATA_KEY_PRODUCT_IDS = 'productIds';
    protected const CONDITIONS_DATA_KEY_CATEGORIES_IDS = 'categoryIds';
    protected const SLOT_DATA_KEY_ID_PRODUCT_ABSTRACT = 'idProductAbstract';

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
     * @param array $conditionData
     * @param array $cmsSlotData
     *
     * @return bool
     */
    public function resolveIsCmsBlockVisibleInSlot(array $conditionData, array $cmsSlotData): bool
    {
        if (isset($conditionData[static::CONDITIONS_DATA_KEY_ALL]) && $conditionData[static::CONDITIONS_DATA_KEY_ALL]) {
            return true;
        }

        $idProductAbstract = (int)$cmsSlotData[static::SLOT_DATA_KEY_ID_PRODUCT_ABSTRACT] ?? null;

        if (!$idProductAbstract) {
            return false;
        }

        if ($this->checkProductConditions($conditionData, $idProductAbstract)) {
            return true;
        }

        return $this->checkCategoryConditions($conditionData, $idProductAbstract);
    }

    /**
     * @param array $conditionData
     * @param int $idProductAbstract
     *
     * @return bool
     */
    protected function checkProductConditions(array $conditionData, int $idProductAbstract): bool
    {
        if (!isset($conditionData[static::CONDITIONS_DATA_KEY_PRODUCT_IDS])) {
            return false;
        }

        return in_array($idProductAbstract, $conditionData[static::CONDITIONS_DATA_KEY_PRODUCT_IDS]);
    }

    /**
     * @param array $conditionData
     * @param int $idProductAbstract
     *
     * @return bool
     */
    protected function checkCategoryConditions(array $conditionData, int $idProductAbstract): bool
    {
        $conditionsDataCategoryIds = $conditionData[static::CONDITIONS_DATA_KEY_CATEGORIES_IDS] ?? null;

        if (!$conditionsDataCategoryIds) {
            return false;
        }

        $localeName = $this->localeClient->getCurrentLocale();
        $productAbstractCategoryStorageTransfer = $this->productCategoryStorageClient->findProductAbstractCategory(
            $idProductAbstract,
            $localeName
        );

        if (!$productAbstractCategoryStorageTransfer) {
            return false;
        }

        $productCategoryIds = $this->getProductCategoryIds($productAbstractCategoryStorageTransfer);

        return count(array_intersect($conditionsDataCategoryIds, $productCategoryIds)) > 0;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractCategoryStorageTransfer $abstractCategoryStorageTransfer
     *
     * @return int[]
     */
    protected function getProductCategoryIds(
        ProductAbstractCategoryStorageTransfer $abstractCategoryStorageTransfer
    ): array {
        $productCategoryStorageTransfers = $abstractCategoryStorageTransfer->getCategories();
        $categoryIds = [];

        foreach ($productCategoryStorageTransfers as $productCategoryStorageTransfer) {
            $categoryIds[] = $productCategoryStorageTransfer->getCategoryId();
        }

        return $categoryIds;
    }
}
