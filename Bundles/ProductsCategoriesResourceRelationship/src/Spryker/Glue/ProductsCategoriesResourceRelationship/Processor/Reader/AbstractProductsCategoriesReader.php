<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsCategoriesResourceRelationship\Processor\Reader;

use Spryker\Glue\ProductsCategoriesResourceRelationship\Dependency\Client\ProductsCategoriesResourceRelationshipToProductCategoryStorageClientInterface;
use Spryker\Glue\ProductsCategoriesResourceRelationship\Dependency\Client\ProductsCategoriesResourceRelationshipToProductStorageClientInterface;

class AbstractProductsCategoriesReader implements AbstractProductsCategoriesReaderInterface
{
    protected const PRODUCT_ABSTRACT_MAPPING_TYPE = 'sku';
    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    protected const KEY_SKU = 'sku';

    /**
     * @var \Spryker\Glue\ProductsCategoriesResourceRelationship\Dependency\Client\ProductsCategoriesResourceRelationshipToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Glue\ProductsCategoriesResourceRelationship\Dependency\Client\ProductsCategoriesResourceRelationshipToProductCategoryStorageClientInterface
     */
    protected $productCategoryStorageClient;

    /**
     * @param \Spryker\Glue\ProductsCategoriesResourceRelationship\Dependency\Client\ProductsCategoriesResourceRelationshipToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\ProductsCategoriesResourceRelationship\Dependency\Client\ProductsCategoriesResourceRelationshipToProductCategoryStorageClientInterface $productCategoryStorageClient
     */
    public function __construct(
        ProductsCategoriesResourceRelationshipToProductStorageClientInterface $productStorageClient,
        ProductsCategoriesResourceRelationshipToProductCategoryStorageClientInterface $productCategoryStorageClient
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->productCategoryStorageClient = $productCategoryStorageClient;
    }

    /**
     * @param string $sku
     * @param string $locale
     *
     * @return int[]|null
     */
    public function findProductCategoryNodeIds(string $sku, string $locale): ?array
    {
        $abstractProductData = $this->productStorageClient
            ->findProductAbstractStorageDataByMapping(
                static::PRODUCT_ABSTRACT_MAPPING_TYPE,
                $sku,
                $locale
            );
        if (!$abstractProductData) {
            return null;
        }

        return $this->getProductCategoryNodeIds($abstractProductData, $locale);
    }

    /**
     * @param string[] $productAbstractSkus
     * @param string $locale
     *
     * @return array
     */
    public function findProductCategoryNodeIdsBySkus(array $productAbstractSkus, string $locale): array
    {
        $productAbstractData = $this->productStorageClient
            ->findBulkProductAbstractStorageDataByMapping(
                static::PRODUCT_ABSTRACT_MAPPING_TYPE,
                $productAbstractSkus,
                $locale
            );
        if (count($productAbstractData) === 0) {
            return [];
        }

        return $this->getBulkProductCategoryNodeIds($productAbstractData, $locale);
    }

    /**
     * @param array $abstractProductData
     * @param string $locale
     *
     * @return array
     */
    protected function getProductCategoryNodeIds(array $abstractProductData, string $locale): array
    {
        $productCategoryNodeIds = [];
        $idProductAbstract = $abstractProductData[static::KEY_ID_PRODUCT_ABSTRACT];
        $productCategories = $this->productCategoryStorageClient
            ->findProductAbstractCategory($idProductAbstract, $locale);

        if ($productCategories) {
            foreach ($productCategories->getCategories() as $productCategory) {
                $productCategoryNodeIds[] = $productCategory->getCategoryNodeId();
            }
        }

        return $productCategoryNodeIds;
    }

    /**
     * @param array $abstractProductData
     * @param string $locale
     *
     * @return array
     */
    protected function getBulkProductCategoryNodeIds(array $abstractProductData, string $locale): array
    {
        $productAbstractIds = [];
        foreach ($abstractProductData as $item) {
            $productAbstractIds[] = (int)$item[static::KEY_ID_PRODUCT_ABSTRACT];
        }

        $productAbstractCategoryStorageTransfers = $this->productCategoryStorageClient
            ->findBulkProductAbstractCategory($productAbstractIds, $locale);

        $productCategoryNodeIds = [];
        foreach ($productAbstractCategoryStorageTransfers as $productAbstractCategoryStorageTransfer) {
            foreach ($productAbstractCategoryStorageTransfer->getCategories() as $productCategoryStorageTransfer) {
                $productCategoryNodeIds[$productAbstractCategoryStorageTransfer->getIdProductAbstract()][]
                    = $productCategoryStorageTransfer->getCategoryNodeId();
            }
        }

        return $productCategoryNodeIds;
    }
}
