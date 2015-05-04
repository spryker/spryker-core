<?php

namespace SprykerFeature\Zed\StockSalesConnector\Dependency\Facade;

use Generated\Shared\Transfer\StockStockProductTransfer;

interface StockToSalesFacadeInterface
{
    /**
     * @param StockProduct $transferStockProduct
     *
     * @return int
     */
    public function updateStockProduct(StockProduct $transferStockProduct);

    /**
     * @param string $sku
     * @param int $decrementBy
     * @param string $stockType
     */
    public function decrementStockProduct($sku, $stockType, $decrementBy = 1);

    /**
     * @param string $sku
     * @param string $stockType
     * @param int $incrementBy
     */
    public function incrementStockProduct($sku, $stockType, $incrementBy = 1);
}
