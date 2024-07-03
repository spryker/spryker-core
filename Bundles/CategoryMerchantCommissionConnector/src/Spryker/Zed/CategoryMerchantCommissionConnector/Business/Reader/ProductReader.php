<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryMerchantCommissionConnector\Business\Reader;

use Generated\Shared\Transfer\ProductConcreteConditionsTransfer;
use Generated\Shared\Transfer\ProductConcreteCriteriaTransfer;
use Spryker\Zed\CategoryMerchantCommissionConnector\Dependency\Facade\CategoryMerchantCommissionConnectorToProductFacadeInterface;

class ProductReader implements ProductReaderInterface
{
    /**
     * @var \Spryker\Zed\CategoryMerchantCommissionConnector\Dependency\Facade\CategoryMerchantCommissionConnectorToProductFacadeInterface
     */
    protected CategoryMerchantCommissionConnectorToProductFacadeInterface $productFacade;

    /**
     * @var array<string, \Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected static array $productConcreteTransfersIndexedBySku = [];

    /**
     * @param \Spryker\Zed\CategoryMerchantCommissionConnector\Dependency\Facade\CategoryMerchantCommissionConnectorToProductFacadeInterface $productFacade
     */
    public function __construct(CategoryMerchantCommissionConnectorToProductFacadeInterface $productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param array<string, list<\Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer>> $merchantCommissionCalculationRequestItemTransfersGroupedBySku
     *
     * @return array<string, \Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductConcreteTransfersIndexedBySku(array $merchantCommissionCalculationRequestItemTransfersGroupedBySku): array
    {
        $productConcreteSkus = array_keys($merchantCommissionCalculationRequestItemTransfersGroupedBySku);
        $notCachedProductConcreteSkus = $this->filterOutCachedProductConcreteSkus($productConcreteSkus);
        if ($notCachedProductConcreteSkus !== []) {
            $this->addProductConcreteTransfersToCache($notCachedProductConcreteSkus);
        }

        return array_intersect_key(static::$productConcreteTransfersIndexedBySku, array_flip($productConcreteSkus));
    }

    /**
     * @param list<string> $productConcreteSkus
     *
     * @return void
     */
    protected function addProductConcreteTransfersToCache(array $productConcreteSkus): void
    {
        $productConcreteConditionsTransfer = (new ProductConcreteConditionsTransfer())->setSkus($productConcreteSkus);
        $productConcreteCriteriaTransfer = (new ProductConcreteCriteriaTransfer())
            ->setProductConcreteConditions($productConcreteConditionsTransfer);

        $productConcreteCollectionTransfer = $this->productFacade->getProductConcreteCollection($productConcreteCriteriaTransfer);
        foreach ($productConcreteCollectionTransfer->getProducts() as $productConcreteTransfer) {
            static::$productConcreteTransfersIndexedBySku[$productConcreteTransfer->getSkuOrFail()] = $productConcreteTransfer;
        }
    }

    /**
     * @param list<string> $productConcreteSkus
     *
     * @return list<string>
     */
    protected function filterOutCachedProductConcreteSkus(array $productConcreteSkus): array
    {
        return array_diff($productConcreteSkus, array_keys(static::$productConcreteTransfersIndexedBySku));
    }
}
