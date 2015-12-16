<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Stock\Business;

use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\TypeTransfer;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\StockSalesConnector\Dependency\Facade\StockToSalesFacadeInterface;

/**
 * @method StockDependencyContainer getBusinessFactory()
 */
class StockFacade extends AbstractFacade implements AvailabilityToStockFacadeInterface, StockToSalesFacadeInterface
{

    /**
     * @param string $sku
     *
     * @return bool
     */
    public function isNeverOutOfStock($sku)
    {
        return $this->getBusinessFactory()->getReaderModel()->isNeverOutOfStock($sku);
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    public function calculateStockForProduct($sku)
    {
        return $this->getBusinessFactory()->getCalculatorModel()->calculateStockForProduct($sku);
    }

    /**
     * @param TypeTransfer $stockTypeTransfer
     *
     * @return int
     */
    public function createStockType(TypeTransfer $stockTypeTransfer)
    {
        return $this->getBusinessFactory()->getWriterModel()->createStockType($stockTypeTransfer);
    }

    /**
     * @param TypeTransfer $stockTypeTransfer
     *
     * @return int
     */
    public function updateStockType(TypeTransfer $stockTypeTransfer)
    {
        return $this->getBusinessFactory()->getWriterModel()->updateStockType($stockTypeTransfer);
    }

    /**
     * @param StockProductTransfer $transferStockProduct
     *
     * @return int
     */
    public function createStockProduct(StockProductTransfer $transferStockProduct)
    {
        return $this->getBusinessFactory()->getWriterModel()->createStockProduct($transferStockProduct);
    }

    /**
     * @param StockProductTransfer $stockProductTransfer
     *
     * @return int
     */
    public function updateStockProduct(StockProductTransfer $stockProductTransfer)
    {
        return $this->getBusinessFactory()->getWriterModel()->updateStockProduct($stockProductTransfer);
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
        $this->getBusinessFactory()->getWriterModel()->decrementStock($sku, $stockType, $decrementBy);
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
        $this->getBusinessFactory()->getWriterModel()->incrementStock($sku, $stockType, $incrementBy);
    }

    /**
     * @param string $sku
     * @param string $stockType
     *
     * @return bool
     */
    public function hasStockProduct($sku, $stockType)
    {
        return $this->getBusinessFactory()->getReaderModel()->hasStockProduct($sku, $stockType);
    }

    /**
     * @param string $sku
     * @param string $stockType
     *
     * @return int
     */
    public function getIdStockProduct($sku, $stockType)
    {
        return $this->getBusinessFactory()->getReaderModel()->getIdStockProduct($sku, $stockType);
    }

}
