<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductRelationStorage\Relation;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductRelationStorage\Dependency\Client\ProductRelationStorageToProductStorageClientInterface;
use Spryker\Client\ProductRelationStorage\Storage\ProductAbstractRelationStorageReaderInterface;
use Spryker\Shared\ProductRelation\ProductRelationTypes;

class RelatedProductReader implements RelatedProductReaderInterface
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
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function findRelatedProducts($idProductAbstract, $localeName)
    {
        $relatedProductAbstractIds = $this->findRelatedAbstractProductIds($idProductAbstract);
        $productStorageDataCollection = $this
            ->productStorageClient
            ->getBulkProductAbstractStorageDataByProductAbstractIdsAndLocaleName($relatedProductAbstractIds, $localeName);

        return $this->mapProductViewTransfers($productStorageDataCollection, $localeName);
    }

    /**
     * @param array $productStorageData
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    protected function mapProductViewTransfers(array $productStorageData, string $localeName): array
    {
        $productViewTransfers = [];
        foreach ($productStorageData as $data) {
            $productViewTransfers[] = $this->createProductView($localeName, $data);
        }

        return $productViewTransfers;
    }

    /**
     * @param int $idProductAbstract
     *
     * @return int[]
     */
    public function findRelatedAbstractProductIds(int $idProductAbstract): array
    {
        $relationIds = $this->getRelationIds($idProductAbstract);

        return $this->getSortedProductAbstractIds($relationIds);
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
            if ($productRelationStorageTransfer->getKey() !== ProductRelationTypes::TYPE_RELATED_PRODUCTS) {
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
