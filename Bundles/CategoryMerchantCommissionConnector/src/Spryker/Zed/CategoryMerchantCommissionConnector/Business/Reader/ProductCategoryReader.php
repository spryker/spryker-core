<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryMerchantCommissionConnector\Business\Reader;

use Generated\Shared\Transfer\ProductCategoryConditionsTransfer;
use Generated\Shared\Transfer\ProductCategoryCriteriaTransfer;
use Spryker\Zed\CategoryMerchantCommissionConnector\Dependency\Facade\CategoryMerchantCommissionConnectorToProductCategoryFacadeInterface;

class ProductCategoryReader implements ProductCategoryReaderInterface
{
    /**
     * @var \Spryker\Zed\CategoryMerchantCommissionConnector\Dependency\Facade\CategoryMerchantCommissionConnectorToProductCategoryFacadeInterface
     */
    protected CategoryMerchantCommissionConnectorToProductCategoryFacadeInterface $productCategoryFacade;

    /**
     * @var array<string, list<\Generated\Shared\Transfer\ProductCategoryTransfer>>
     */
    protected static array $productCategoryTransfersGroupedBySku = [];

    /**
     * @param \Spryker\Zed\CategoryMerchantCommissionConnector\Dependency\Facade\CategoryMerchantCommissionConnectorToProductCategoryFacadeInterface $productCategoryFacade
     */
    public function __construct(CategoryMerchantCommissionConnectorToProductCategoryFacadeInterface $productCategoryFacade)
    {
        $this->productCategoryFacade = $productCategoryFacade;
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfersIndexedBySku
     *
     * @return array<string, list<\Generated\Shared\Transfer\ProductCategoryTransfer>>
     */
    public function getProductCategoriesGroupedByProductConcreteSku(array $productConcreteTransfersIndexedBySku): array
    {
        $productConcreteSkus = array_keys($productConcreteTransfersIndexedBySku);
        $notCachedProductConcreteSkus = $this->filterOutCachedProductConcreteSkus($productConcreteSkus);
        if ($notCachedProductConcreteSkus !== []) {
            $this->addProductCategoryTransfersToCache(
                array_intersect_key($productConcreteTransfersIndexedBySku, array_flip($notCachedProductConcreteSkus)),
            );
        }

        return array_intersect_key(static::$productCategoryTransfersGroupedBySku, array_flip($productConcreteSkus));
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfersIndexedBySku
     *
     * @return void
     */
    protected function addProductCategoryTransfersToCache(array $productConcreteTransfersIndexedBySku): void
    {
        $productCategoryTransfersIndexedByIdProductAbstract = $this->getProductConcreteTransfersIndexedByIdProductAbstract(
            $productConcreteTransfersIndexedBySku,
        );
        $productCategoryConditionsTransfer = (new ProductCategoryConditionsTransfer())
            ->setProductAbstractIds(array_keys($productCategoryTransfersIndexedByIdProductAbstract));
        $productCategoryCriteriaTransfer = (new ProductCategoryCriteriaTransfer())
            ->setProductCategoryConditions($productCategoryConditionsTransfer);

        $productCategoryCollectionTransfer = $this->productCategoryFacade->getProductCategoryCollection(
            $productCategoryCriteriaTransfer,
        );

        foreach ($productCategoryCollectionTransfer->getProductCategories() as $productCategoryTransfer) {
            $idProductAbstract = $productCategoryTransfer->getFkProductAbstractOrFail();
            if (!isset($productCategoryTransfersIndexedByIdProductAbstract[$idProductAbstract])) {
                continue;
            }

            $productConcreteSku = $productCategoryTransfersIndexedByIdProductAbstract[$idProductAbstract]->getSkuOrFail();
            static::$productCategoryTransfersGroupedBySku[$productConcreteSku][] = $productCategoryTransfer;
        }
    }

    /**
     * @param list<string> $productConcreteSkus
     *
     * @return list<string>
     */
    protected function filterOutCachedProductConcreteSkus(array $productConcreteSkus): array
    {
        return array_diff($productConcreteSkus, array_keys(static::$productCategoryTransfersGroupedBySku));
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected function getProductConcreteTransfersIndexedByIdProductAbstract(array $productConcreteTransfers): array
    {
        $indexedProductConcreteTransfers = [];
        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $indexedProductConcreteTransfers[$productConcreteTransfer->getFkProductAbstractOrFail()] = $productConcreteTransfer;
        }

        return $indexedProductConcreteTransfers;
    }
}
