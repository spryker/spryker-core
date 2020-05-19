<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Expander;

use Spryker\Zed\ProductBundle\Business\ProductBundle\Calculation\ProductBundlePriceCalculationInterface;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface;

class ItemExpander implements ItemExpanderInterface
{
    /**
     * @uses \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepository::COL_ID_PRODUCT
     */
    protected const COL_ID_PRODUCT = 'id_product';

    /**
     * @uses \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepository::COL_FK_PRODUCT_ABSTRACT
     */
    protected const COL_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';

    /**
     * @var \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface
     */
    protected $productBundleRepository;

    /**
     * @var \Spryker\Zed\ProductBundle\Business\ProductBundle\Calculation\ProductBundlePriceCalculationInterface
     */
    protected $productBundlePriceCalculation;

    /**
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleRepositoryInterface $productBundleRepository
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundle\Calculation\ProductBundlePriceCalculationInterface $productBundlePriceCalculation
     */
    public function __construct(
        ProductBundleRepositoryInterface $productBundleRepository,
        ProductBundlePriceCalculationInterface $productBundlePriceCalculation
    ) {
        $this->productBundleRepository = $productBundleRepository;
        $this->productBundlePriceCalculation = $productBundlePriceCalculation;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    public function expandItemsWithProductBundles(array $itemTransfers): array
    {
        $bundleItemTransfers = $this->productBundleRepository->getBundleItemsBySalesOrderItemIds(
            $this->getSalesOrderItemIds($itemTransfers)
        );

        if (!$bundleItemTransfers) {
            return $itemTransfers;
        }

        $bundleItemTransfers = $this->expandBundleItemsWithIds($bundleItemTransfers);

        foreach ($itemTransfers as $itemTransfer) {
            if (!isset($bundleItemTransfers[$itemTransfer->getIdSalesOrderItem()])) {
                continue;
            }

            $bundleItemTransfer = $bundleItemTransfers[$itemTransfer->getIdSalesOrderItem()];
            $itemTransfer->setProductBundle($bundleItemTransfer)
                ->setRelatedBundleItemIdentifier($bundleItemTransfer->getBundleItemIdentifier());
            $this->productBundlePriceCalculation->calculateBundleAmounts($bundleItemTransfer, $itemTransfer);
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $bundleItemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function expandBundleItemsWithIds(array $bundleItemTransfers): array
    {
        $productConcretesRawData = $this->productBundleRepository->getProductConcretesRawDataByProductConcreteSkus(
            $this->getProductConcreteSkus($bundleItemTransfers)
        );

        foreach ($bundleItemTransfers as $bundleItemTransfer) {
            if (!isset($productConcretesRawData[$bundleItemTransfer->getSku()])) {
                continue;
            }

            $bundleItemTransfer->setId($productConcretesRawData[$bundleItemTransfer->getSku()][static::COL_ID_PRODUCT])
                ->setIdProductAbstract($productConcretesRawData[$bundleItemTransfer->getSku()][static::COL_FK_PRODUCT_ABSTRACT]);
        }

        return $bundleItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return string[]
     */
    protected function getProductConcreteSkus(array $itemTransfers): array
    {
        $productConcreteSkus = [];

        foreach ($itemTransfers as $itemTransfer) {
            $productConcreteSkus[] = $itemTransfer->getSku();
        }

        return $productConcreteSkus;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return int[]
     */
    protected function getSalesOrderItemIds(array $itemTransfers): array
    {
        $salesOrderItemIds = [];

        foreach ($itemTransfers as $itemTransfer) {
            $salesOrderItemIds[] = $itemTransfer->getIdSalesOrderItem();
        }

        return $salesOrderItemIds;
    }
}
