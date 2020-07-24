<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStockGui\Communication\Reader\ProductOfferStock;

use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductOfferStockGui\Dependency\Facade\ProductOfferStockGuiToProductOfferStockFacadeInterface;

class ProductOfferStockReader implements ProductOfferStockReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferStockGui\Dependency\Facade\ProductOfferStockGuiToProductOfferStockFacadeInterface
     */
    protected $productOfferStockFacade;

    /**
     * @var \Spryker\Zed\ProductOfferStockGuiExtension\Dependeency\Plugin\ProductOfferStockTableExpanderPluginInterface[]
     */
    protected $productOfferStockTableExpanderPlugins;

    /**
     * @param \Spryker\Zed\ProductOfferStockGui\Dependency\Facade\ProductOfferStockGuiToProductOfferStockFacadeInterface $productOfferStockFacade
     * @param \Spryker\Zed\ProductOfferStockGuiExtension\Dependeency\Plugin\ProductOfferStockTableExpanderPluginInterface[] $productOfferStockTableExpanderPlugins
     */
    public function __construct(
        ProductOfferStockGuiToProductOfferStockFacadeInterface $productOfferStockFacade,
        array $productOfferStockTableExpanderPlugins
    ) {
        $this->productOfferStockFacade = $productOfferStockFacade;
        $this->productOfferStockTableExpanderPlugins = $productOfferStockTableExpanderPlugins;
    }

    /**
     * @phpstan-return array<mixed>
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return array
     */
    public function getProductOfferStockData(ProductOfferTransfer $productOfferTransfer): array
    {
        $productOfferStocks = [];
        $productOfferStockRequestTransfer = (new ProductOfferStockRequestTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference());

        foreach ($productOfferTransfer->getStores() as $storeTransfer) {
            $productOfferStockRequestTransfer->setStore($storeTransfer);

            $productOfferStockTransfer = $this->productOfferStockFacade
                ->getProductOfferStock($productOfferStockRequestTransfer);

            $productOfferStockTransfer->setProductOfferReference($productOfferTransfer->getProductOfferReference());

            $productOfferStocks[] = [
                'name' => $productOfferStockTransfer->getStock()->getName(),
                'quantity' => $productOfferStockTransfer->getQuantity(),
                'isNeverOutOfStock' => $productOfferStockTransfer->getIsNeverOutOfStock(),
                'storeName' => $storeTransfer->getName(),
                'additional' => $this->executeProductOfferStockTableExpanderPlugins($productOfferStockTransfer, $storeTransfer),
            ];
        }

        return [
            'productOfferStocks' => $productOfferStocks,
        ];
    }

    /**
     * @phpstan-return array<mixed>
     *
     * @param \Generated\Shared\Transfer\ProductOfferStockTransfer $productOfferStockTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array
     */
    protected function executeProductOfferStockTableExpanderPlugins(
        ProductOfferStockTransfer $productOfferStockTransfer,
        StoreTransfer $storeTransfer
    ): array {
        $additionalTableData = [];

        foreach ($this->productOfferStockTableExpanderPlugins as $productOfferStockTableExpanderPlugin) {
            $additionalTableData['headers'][] = $productOfferStockTableExpanderPlugin->getHeader();
            $additionalTableData['columns'][] = $productOfferStockTableExpanderPlugin->getColumnData(
                $productOfferStockTransfer,
                $storeTransfer
            );
        }

        return $additionalTableData;
    }
}
