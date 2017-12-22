<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductRelationStorage\Relation;

use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ProductRelationStorage\Dependency\Client\ProductRelationStorageToProductStorageClientInterface;
use Spryker\Client\ProductRelationStorage\Storage\ProductAbstractRelationStorageReaderInterface;
use Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface;
use Spryker\Shared\ProductRelation\ProductRelationTypes;

class UpSellingProductReader implements UpSellingProductReaderInterface
{
    /**
     * @var ProductAbstractRelationStorageReaderInterface
     */
    protected $productAbstractRelationStorageReader;

    /**
     * @var ProductRelationStorageToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var ProductViewExpanderPluginInterface[]
     */
    protected $productViewExpanderPlugins;

    /**
     * @param ProductAbstractRelationStorageReaderInterface $productAbstractRelationStorageReader
     * @param ProductRelationStorageToProductStorageClientInterface $productStorageClient
     * @param ProductViewExpanderPluginInterface[] $productViewExpanderPlugins
     */
    public function __construct(
        ProductAbstractRelationStorageReaderInterface $productAbstractRelationStorageReader,
        ProductRelationStorageToProductStorageClientInterface $productStorageClient,
        array $productViewExpanderPlugins
    ) {
        $this->productAbstractRelationStorageReader = $productAbstractRelationStorageReader;
        $this->productStorageClient = $productStorageClient;
        $this->productViewExpanderPlugins = $productViewExpanderPlugins;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     * @param string $localeName
     *
     * @return ProductViewTransfer[]
     */
    public function findUpSellingProducts(QuoteTransfer $quoteTransfer, $localeName)
    {
        $productAbstractIds = $this->findSubjectProductAbstractIds($quoteTransfer);
        $relationIds = $this->findRelationIds($localeName, $productAbstractIds);
        $productAbstractIds = $this->getSortedProductAbstractIds($relationIds);

        $relatedProducts = [];
        foreach ($productAbstractIds as $idProductAbstract) {
            $productStorageData = $this->productStorageClient->getProductAbstractStorageData($idProductAbstract, $localeName);
            $relatedProducts[] = $this->createProductView($localeName, $productStorageData);
        }

        return $relatedProducts;
    }

    /**
     * @param QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    protected function findSubjectProductAbstractIds(QuoteTransfer $quoteTransfer): array
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
     * @param $localeName
     * @param $productAbstractIds
     *
     * @return array
     */
    protected function findRelationIds($localeName, $productAbstractIds): array
    {
        $relationIds = [];
        foreach ($productAbstractIds as $idProductAbstract) {
            $relationIds = array_replace($relationIds, $this->getRelationIds($idProductAbstract, $localeName));
        }

        return $relationIds;
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return array
     */
    protected function getRelationIds($idProductAbstract, $localeName)
    {
        $productAbstractRelationStorageTransfer = $this->productAbstractRelationStorageReader->findProductAbstractRelation($idProductAbstract, $localeName);

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
     * @return ProductViewTransfer
     */
    protected function createProductView($localeName, array $productStorageData): ProductViewTransfer
    {
        $productViewTransfer = new ProductViewTransfer();
        $productViewTransfer->fromArray($productStorageData, true);

        foreach ($this->productViewExpanderPlugins as $productViewExpanderPlugin) {
            $productViewTransfer = $productViewExpanderPlugin->expandProductViewTransfer($productViewTransfer, $productStorageData, $localeName);
        }

        return $productViewTransfer;
    }
}
