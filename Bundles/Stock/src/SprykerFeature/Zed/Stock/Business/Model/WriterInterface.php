<?php

namespace SprykerFeature\Zed\Stock\Business\Model;

use Generated\Shared\Transfer\StockStockProductTransfer;
use Generated\Shared\Transfer\StockStockTypeTransfer;

interface WriterInterface
{
    /**
     * @param StockStockTypeTransfer $stockTypeTransfer
     *
     * @return int
     */
    public function createStockType(StockStockTypeTransfer $stockTypeTransfer);

    /**
     * @param StockStockTypeTransfer $stockTypeTransfer
     *
     * @return int
     */
    public function updateStockType(StockStockTypeTransfer $stockTypeTransfer);

    /**
     * @param StockStockProductTransfer $transferStockProduct
     *
     * @return int
     */
    public function updateStockProduct(StockStockProductTransfer $transferStockProduct);

    /**
     * @param string $sku
     * @param int $decrementBy
     * @param string $stockType
     */
    public function decrementStock($sku, $stockType, $decrementBy = 1);

    /**
     * @param string $sku
     * @param int $incrementBy
     * @param string $stockType
     */
    public function incrementStock($sku, $stockType, $incrementBy = 1);

    /**
     * @param StockStockProductTransfer $transferStockProduct
     *
     * @return int
     */
    public function createStockProduct(StockStockProductTransfer $transferStockProduct);
}
