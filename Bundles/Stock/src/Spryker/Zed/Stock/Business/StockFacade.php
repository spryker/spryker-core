<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Stock\Business;

use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\TypeTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method StockBusinessFactory getFactory()
 */
class StockFacade extends AbstractFacade
{

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function isNeverOutOfStock($sku)
    {
        return $this->getFactory()->createReaderModel()->isNeverOutOfStock($sku);
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    public function calculateStockForProduct($sku)
    {
        return $this->getFactory()->createCalculatorModel()->calculateStockForProduct($sku);
    }

    /**
     * @param \Generated\Shared\Transfer\TypeTransfer $stockTypeTransfer
     *
     * @return int
     */
    public function createStockType(TypeTransfer $stockTypeTransfer)
    {
        return $this->getFactory()->createWriterModel()->createStockType($stockTypeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\TypeTransfer $stockTypeTransfer
     *
     * @return int
     */
    public function updateStockType(TypeTransfer $stockTypeTransfer)
    {
        return $this->getFactory()->createWriterModel()->updateStockType($stockTypeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\StockProductTransfer $transferStockProduct
     *
     * @return int
     */
    public function createStockProduct(StockProductTransfer $transferStockProduct)
    {
        return $this->getFactory()->createWriterModel()->createStockProduct($transferStockProduct);
    }

    /**
     * @param \Generated\Shared\Transfer\StockProductTransfer $stockProductTransfer
     *
     * @return int
     */
    public function updateStockProduct(StockProductTransfer $stockProductTransfer)
    {
        return $this->getFactory()->createWriterModel()->updateStockProduct($stockProductTransfer);
    }

    /**
     * @param string $sku
     * @param int $decrementBy
     * @param string $stockType
     *
     * @return void
     */
    public function decrementStockProduct($sku, $stockType, $decrementBy = 1)
    {
        $this->getFactory()->createWriterModel()->decrementStock($sku, $stockType, $decrementBy);
    }

    /**
     * @param string $sku
     * @param int $incrementBy
     * @param string $stockType
     *
     * @return void
     */
    public function incrementStockProduct($sku, $stockType, $incrementBy = 1)
    {
        $this->getFactory()->createWriterModel()->incrementStock($sku, $stockType, $incrementBy);
    }

    /**
     * @param string $sku
     * @param string $stockType
     *
     * @return bool
     */
    public function hasStockProduct($sku, $stockType)
    {
        return $this->getFactory()->createReaderModel()->hasStockProduct($sku, $stockType);
    }

    /**
     * @param string $sku
     * @param string $stockType
     *
     * @return int
     */
    public function getIdStockProduct($sku, $stockType)
    {
        return $this->getFactory()->createReaderModel()->getIdStockProduct($sku, $stockType);
    }

}
