<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Reader;

use Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface;

class AddToCartSkuReader implements AddToCartSkuReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface
     */
    protected $productPageSearchRepository;

    /**
     * @var \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductAbstractAddToCartPluginInterface[]
     */
    protected $productAbstractAddToCartPlugins;

    /**
     * @param \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface $productPageSearchRepository
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductAbstractAddToCartPluginInterface[] $productAbstractAddToCartPlugins
     */
    public function __construct(
        ProductPageSearchRepositoryInterface $productPageSearchRepository,
        array $productAbstractAddToCartPlugins
    ) {
        $this->productPageSearchRepository = $productPageSearchRepository;
        $this->productAbstractAddToCartPlugins = $productAbstractAddToCartPlugins;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return string[]
     */
    public function getProductAbstractAddToCartSkus(array $productAbstractIds): array
    {
        $productAbstractIds = $this->productPageSearchRepository->getEligibleForAddToCartProductAbstractsIds($productAbstractIds);

        if (!$productAbstractIds) {
            return [];
        }

        if (!$this->productAbstractAddToCartPlugins) {
            return $this->productPageSearchRepository->getProductConcreteSkusByProductAbstractIds($productAbstractIds);
        }

        $productConcreteTransfers = $this->productPageSearchRepository->getConcreteProductsByProductAbstractIds($productAbstractIds);
        $productConcreteTransfers = $this->executeProductAbstractAddToCartPlugins($productConcreteTransfers);

        return $this->mapProductConcreteTransfersToProductConcreteSkus($productConcreteTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    protected function executeProductAbstractAddToCartPlugins(array $productConcreteTransfers): array
    {
        foreach ($this->productAbstractAddToCartPlugins as $productAbstractAddToCartPlugin) {
            $productConcreteTransfers = $productAbstractAddToCartPlugin->getEligibleConcreteProducts($productConcreteTransfers);
        }

        return $productConcreteTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return string[]
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
