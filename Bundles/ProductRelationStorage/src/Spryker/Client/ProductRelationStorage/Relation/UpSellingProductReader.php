<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductRelationStorage\Relation;

use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ProductRelationStorage\Dependency\Client\ProductRelationStorageToProductStorageClientInterface;
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
     * @var \Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface[]
     */
    protected $productViewExpanderPlugins;

    /**
     * @param \Spryker\Client\ProductRelationStorage\Storage\ProductAbstractRelationStorageReaderInterface $productAbstractRelationStorageReader
     * @param \Spryker\Client\ProductRelationStorage\Dependency\Client\ProductRelationStorageToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Client\ProductStorage\Dependency\Plugin\ProductViewExpanderPluginInterface[] $productViewExpanderPlugins
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function findUpSellingProducts(QuoteTransfer $quoteTransfer, $localeName)
    {
        $productAbstractIds = $this->findSubjectProductAbstractIds($quoteTransfer);
        $relationIds = $this->findRelationIds($productAbstractIds);
        $productAbstractIds = $this->getSortedProductAbstractIds($relationIds);

        $relatedProducts = [];
        foreach ($productAbstractIds as $idProductAbstract) {
            $productStorageData = $this->productStorageClient->findProductAbstractStorageData($idProductAbstract, $localeName);
            if ($productStorageData !== null) {
                $relatedProducts[] = $this->createProductView($localeName, $productStorageData);
            }
        }

        return $relatedProducts;
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
     * @param array $productAbstractIds
     *
     * @return array
     */
    protected function findRelationIds(array $productAbstractIds)
    {
        $relationIds = [];
        foreach ($productAbstractIds as $idProductAbstract) {
            $relationIds = array_replace($relationIds, $this->getRelationIds($idProductAbstract));
        }

        return $relationIds;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array
     */
    protected function getRelationIds($idProductAbstract)
    {
        $productAbstractRelationStorageTransfer = $this->productAbstractRelationStorageReader->findProductAbstractRelation($idProductAbstract);

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

        foreach ($this->productViewExpanderPlugins as $productViewExpanderPlugin) {
            $productViewTransfer = $productViewExpanderPlugin->expandProductViewTransfer($productViewTransfer, $productStorageData, $localeName);
        }

        return $productViewTransfer;
    }
}
