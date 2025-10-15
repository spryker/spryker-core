<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductRelationStorage\Relation;

use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ProductRelationStorage\Dependency\Client\ProductRelationStorageToProductStorageClientInterface;
use Spryker\Client\ProductRelationStorage\ProductRelationStorageConfig;
use Spryker\Client\ProductRelationStorage\Storage\ProductAbstractRelationStorageReaderInterface;
use Spryker\Shared\ProductRelation\ProductRelationTypes;

class UpSellingProductReader implements UpSellingProductReaderInterface
{
    /**
     * @var \Spryker\Client\ProductRelationStorage\Storage\ProductAbstractRelationStorageReaderInterface
     */
    protected $productAbstractRelationStorageReader;

    /**
     * @var \Spryker\Client\ProductRelationStorage\Dependency\Client\ProductRelationStorageToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var array<\Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface>
     */
    protected $productViewExpanderPlugins;

    protected ProductRelationStorageConfig $productRelationStorageConfig;

    /**
     * @param \Spryker\Client\ProductRelationStorage\Storage\ProductAbstractRelationStorageReaderInterface $productAbstractRelationStorageReader
     * @param \Spryker\Client\ProductRelationStorage\Dependency\Client\ProductRelationStorageToProductStorageClientInterface $productStorageClient
     * @param array<\Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface> $productViewExpanderPlugins
     * @param \Spryker\Client\ProductRelationStorage\ProductRelationStorageConfig $productRelationStorageConfig
     */
    public function __construct(
        ProductAbstractRelationStorageReaderInterface $productAbstractRelationStorageReader,
        ProductRelationStorageToProductStorageClientInterface $productStorageClient,
        array $productViewExpanderPlugins,
        ProductRelationStorageConfig $productRelationStorageConfig
    ) {
        $this->productAbstractRelationStorageReader = $productAbstractRelationStorageReader;
        $this->productStorageClient = $productStorageClient;
        $this->productViewExpanderPlugins = $productViewExpanderPlugins;
        $this->productRelationStorageConfig = $productRelationStorageConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $localeName
     *
     * @return array<\Generated\Shared\Transfer\ProductViewTransfer>
     */
    public function findUpSellingProducts(QuoteTransfer $quoteTransfer, $localeName)
    {
        $quoteTransfer->requireStore();
        $storeTransfer = $quoteTransfer->getStore();
        $upSellingProductAbstractIds = $this->findUpSellingAbstractProductIds($quoteTransfer);

        if (!$upSellingProductAbstractIds) {
            return [];
        }
        $upSellingProductAbstractIds = $this->limitUpSellingProductAbstractIds($upSellingProductAbstractIds);

        $relatedProducts = [];
        $productStorageData = $this->productStorageClient->getBulkProductAbstractStorageDataByProductAbstractIdsForLocaleNameAndStore(
            $upSellingProductAbstractIds,
            $localeName,
            $storeTransfer->getName(),
        );

        foreach ($productStorageData as $productStorageDatum) {
            $relatedProducts[] = $this->createProductView($localeName, $productStorageDatum);
        }

        return $relatedProducts;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<int>
     */
    public function findUpSellingAbstractProductIds(QuoteTransfer $quoteTransfer): array
    {
        $quoteTransfer->requireStore();
        $productAbstractIds = $this->findSubjectProductAbstractIds($quoteTransfer);
        $storeTransfer = $quoteTransfer->getStore();
        $relationIds = $this->findRelationIds($productAbstractIds, $storeTransfer->getName());

        return $this->getSortedProductAbstractIds($relationIds);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function findSubjectProductAbstractIds(QuoteTransfer $quoteTransfer)
    {
        $productAbstractIds = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getRelatedBundleItemIdentifier()) {
                continue;
            }
            $productAbstractIds[$itemTransfer->getIdProductAbstract()] = $itemTransfer->getIdProductAbstract();
        }

        foreach ($quoteTransfer->getBundleItems() as $itemTransfer) {
            $productAbstractIds[$itemTransfer->getIdProductAbstract()] = $itemTransfer->getIdProductAbstract();
        }

        return $productAbstractIds;
    }

    /**
     * @param array<int> $productAbstractIds
     * @param string $storeName
     *
     * @return array
     */
    protected function findRelationIds(array $productAbstractIds, string $storeName)
    {
        $relationIds = [];
        foreach ($productAbstractIds as $idProductAbstract) {
            $relationIds = array_replace($relationIds, $this->getRelationIds($idProductAbstract, $storeName));
        }

        return $relationIds;
    }

    /**
     * @param int $idProductAbstract
     * @param string $storeName
     *
     * @return array
     */
    protected function getRelationIds($idProductAbstract, string $storeName)
    {
        $productAbstractRelationStorageTransfer = $this->productAbstractRelationStorageReader->findProductAbstractRelation($idProductAbstract, $storeName);

        if (!$productAbstractRelationStorageTransfer) {
            return [];
        }

        foreach ($productAbstractRelationStorageTransfer->getProductRelations() as $productRelationStorageTransfer) {
            if ($productRelationStorageTransfer->getKey() !== ProductRelationTypes::TYPE_UP_SELLING) {
                continue;
            }

            if ($productRelationStorageTransfer->getIsActive() !== true) {
                return [];
            }

            return $productRelationStorageTransfer->getProductAbstractIds();
        }

        return [];
    }

    /**
     * @param array $relationIds
     *
     * @return array
     */
    protected function getSortedProductAbstractIds(array $relationIds)
    {
        asort($relationIds, SORT_NATURAL);

        return array_keys($relationIds);
    }

    /**
     * @param string $localeName
     * @param array $productStorageData
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function createProductView($localeName, array $productStorageData)
    {
        $productViewTransfer = new ProductViewTransfer();
        $productViewTransfer->fromArray($productStorageData, true);
        $productViewTransfer->setAbstractSku($productViewTransfer->getSku());

        foreach ($this->productViewExpanderPlugins as $productViewExpanderPlugin) {
            $productViewTransfer = $productViewExpanderPlugin->expandProductViewTransfer($productViewTransfer, $productStorageData, $localeName);
        }

        return $productViewTransfer;
    }

    /**
     * @param array<int> $upSellingProductAbstractIds
     *
     * @return array<int>
     */
    protected function limitUpSellingProductAbstractIds(array $upSellingProductAbstractIds): array
    {
        $upsellingProductLimit = $this->productRelationStorageConfig->getUpsellingProductLimit();
        if (count($upSellingProductAbstractIds) <= $upsellingProductLimit) {
            return $upSellingProductAbstractIds;
        }

        return array_slice($upSellingProductAbstractIds, 0, $upsellingProductLimit);
    }
}
