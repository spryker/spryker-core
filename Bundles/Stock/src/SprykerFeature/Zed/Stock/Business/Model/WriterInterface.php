<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Stock\Business\Model;

use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\TypeTransfer;

interface WriterInterface
{

    /**
     * @param TypeTransfer $stockTypeTransfer
     *
     * @return int
     */
    public function createStockType(TypeTransfer $stockTypeTransfer);

    /**
     * @param TypeTransfer $stockTypeTransfer
     *
     * @return int
     */
    public function updateStockType(TypeTransfer $stockTypeTransfer);

    /**
     * @param StockProductTransfer $transferStockProduct
     *
     * @return int
     */
    public function updateStockProduct(StockProductTransfer $transferStockProduct);

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
     * @param StockProductTransfer $transferStockProduct
     *
     * @return int
     */
    public function createStockProduct(StockProductTransfer $transferStockProduct);

}
