<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Reader;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface;

class AddToCartSkuReader implements AddToCartSkuReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface
     */
    protected $productPageSearchRepository;

    /**
     * @var array<\Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductAbstractAddToCartPluginInterface>
     */
    protected $productAbstractAddToCartPlugins;

    /**
     * @param \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface $productPageSearchRepository
     * @param array<\Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductAbstractAddToCartPluginInterface> $productAbstractAddToCartPlugins
     */
    public function __construct(
        ProductPageSearchRepositoryInterface $productPageSearchRepository,
        array $productAbstractAddToCartPlugins
    ) {
        $this->productPageSearchRepository = $productPageSearchRepository;
        $this->productAbstractAddToCartPlugins = $productAbstractAddToCartPlugins;
    }

    /**
     * @param array<int> $productAbstractIds
     * @param array<array<int>> $productAbstractStoreIds
     *
     * @return array<string>
     */
    public function getProductAbstractAddToCartSkus(array $productAbstractIds, array $productAbstractStoreIds): array
    {
        $productAbstractIds = $this->productPageSearchRepository->getEligibleForAddToCartProductAbstractsIds($productAbstractIds);

        if (!$productAbstractIds) {
            return [];
        }

        if (!$this->productAbstractAddToCartPlugins) {
            return $this->productPageSearchRepository->getProductConcreteSkusByProductAbstractIds($productAbstractIds);
        }

        $productConcreteTransfers = $this->productPageSearchRepository->getConcreteProductsByProductAbstractIds($productAbstractIds);
        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            if (!isset($productAbstractStoreIds[$productConcreteTransfer->getFkProductAbstract()])) {
                continue;
            }

            foreach ($productAbstractStoreIds[$productConcreteTransfer->getFkProductAbstract()] as $storeId) {
                $productConcreteTransfer->addStores(
                    (new StoreTransfer())
                        ->setIdStore($storeId),
                );
            }
        }
        $productConcreteTransfers = $this->executeProductAbstractAddToCartPlugins($productConcreteTransfers);

        return $this->mapProductConcreteTransfersToProductConcreteSkus($productConcreteTransfers);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected function executeProductAbstractAddToCartPlugins(array $productConcreteTransfers): array
    {
        foreach ($this->productAbstractAddToCartPlugins as $productAbstractAddToCartPlugin) {
            $productConcreteTransfers = $productAbstractAddToCartPlugin->getEligibleConcreteProducts($productConcreteTransfers);
        }

        return $productConcreteTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<string>
     */
    protected function mapProductConcreteTransfersToProductConcreteSkus(array $productConcreteTransfers): array
    {
        $productConcreteSkuMapByIdProductAbstract = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productConcreteSkuMapByIdProductAbstract[$productConcreteTransfer->getFkProductAbstract()] = $productConcreteTransfer->getSku();
        }

        return $productConcreteSkuMapByIdProductAbstract;
    }
}
