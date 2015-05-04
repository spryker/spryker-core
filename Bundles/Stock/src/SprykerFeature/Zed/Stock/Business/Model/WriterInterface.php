<?php

namespace SprykerFeature\Zed\Stock\Business\Model;

use Generated\Shared\Transfer\StockStockProductTransfer;
use Generated\Shared\Transfer\StockStockTypeTransfer;

interface WriterInterface
{
    /**
     * @param StockType $stockTypeTransfer
     *
     * @return int
     */
    public function createStockType(StockType $stockTypeTransfer);

    /**
     * @param StockType $stockTypeTransfer
     *
     * @return int
     */
    public function updateStockType(StockType $stockTypeTransfer);

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
    public function decrementStock($sku, $stockType, $decrementBy = 1);

    /**
     * @param string $sku
     * @param int $incrementBy
     * @param string $stockType
     */
    public function incrementStock($sku, $stockType, $incrementBy = 1);

    /**
     * @param StockProduct $transferStockProduct
     *
     * @return int
     */
    public function createStockProduct(StockProduct $transferStockProduct);
}
