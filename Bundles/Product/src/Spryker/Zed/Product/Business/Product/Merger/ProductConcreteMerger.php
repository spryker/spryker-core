<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Merger;

class ProductConcreteMerger implements ProductConcreteMergerInterface
{
    /**
     * @var array<\Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductDataMergerInterface>
     */
    protected array $productDataMergers;

    /**
     * @var array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteMergerPluginInterface>
     */
    protected array $productMergerPlugins;

    /**
     * @param array<\Spryker\Zed\Product\Business\Product\Merger\DataMerger\ProductDataMergerInterface> $productDataMergers
     * @param array<\Spryker\Zed\ProductExtension\Dependency\Plugin\ProductConcreteMergerPluginInterface> $productMergerPlugins
     */
    public function __construct(
        array $productDataMergers,
        array $productMergerPlugins
    ) {
        $this->productDataMergers = $productDataMergers;
        $this->productMergerPlugins = $productMergerPlugins;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     * @param array<int, \Generated\Shared\Transfer\ProductAbstractTransfer> $productAbstractTransfersIndexedByProductAbstractId
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function mergeProductConcreteTransfersWithProductAbstractTransfers(
        array $productConcreteTransfers,
        array $productAbstractTransfersIndexedByProductAbstractId
    ): array {
        $productConcreteTransfers = $this->mergeProductData(
            $productConcreteTransfers,
            $productAbstractTransfersIndexedByProductAbstractId,
        );

        return $this->executeMergerPlugins($productConcreteTransfers, $productAbstractTransfersIndexedByProductAbstractId);
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     * @param array<int, \Generated\Shared\Transfer\ProductAbstractTransfer> $productAbstractTransfersIndexedByProductAbstractId
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected function mergeProductData(
        array $productConcreteTransfers,
        array $productAbstractTransfersIndexedByProductAbstractId
    ): array {
        foreach ($this->productDataMergers as $productDataMerger) {
            $productConcreteTransfers = $productDataMerger
                ->merge(
                    $productConcreteTransfers,
                    $productAbstractTransfersIndexedByProductAbstractId,
                );
        }

        return $productConcreteTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     * @param array<int, \Generated\Shared\Transfer\ProductAbstractTransfer> $productAbstractTransfersIndexedByProductAbstractId
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected function executeMergerPlugins(
        array $productConcreteTransfers,
        array $productAbstractTransfersIndexedByProductAbstractId
    ): array {
        foreach ($this->productMergerPlugins as $productMergerPlugin) {
            foreach ($productConcreteTransfers as $key => $productConcreteTransfer) {
                if (isset($productAbstractTransfersIndexedByProductAbstractId[$productConcreteTransfer->getFkProductAbstract()])) {
                    $productConcreteTransfers[$key] = $productMergerPlugin
                        ->merge(
                            $productConcreteTransfer,
                            $productAbstractTransfersIndexedByProductAbstractId[$productConcreteTransfer->getFkProductAbstract()],
                        );
                }
            }
        }

        return $productConcreteTransfers;
    }
}
