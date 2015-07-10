<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Stock\Business;

use Generated\Shared\Transfer\StockProductTransfer;
use Generated\Shared\Transfer\TypeTransfer;
use SprykerFeature\Zed\Availability\Dependency\Facade\AvailabilityToStockFacadeInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerFeature\Zed\StockSalesConnector\Dependency\Facade\StockToSalesFacadeInterface;

/**
 * @method StockDependencyContainer getDependencyContainer()
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
        return $this->getDependencyContainer()->getReaderModel()->isNeverOutOfStock($sku);
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    public function calculateStockForProduct($sku)
    {
        return $this->getDependencyContainer()->getCalculatorModel()->calculateStockForProduct($sku);
    }

    /**
     * @param TypeTransfer $stockTypeTransfer
     *
     * @return int
     */
    public function createStockType(TypeTransfer $stockTypeTransfer)
    {
        return $this->getDependencyContainer()->getWriterModel()->createStockType($stockTypeTransfer);
    }

    /**
     * @param TypeTransfer $stockTypeTransfer
     *
     * @return int
     */
    public function updateStockType(TypeTransfer $stockTypeTransfer)
    {
        return $this->getDependencyContainer()->getWriterModel()->updateStockType($stockTypeTransfer);
    }

    /**
     * @param StockProductTransfer $transferStockProduct
     *
     * @return int
     */
    public function createStockProduct(StockProductTransfer $transferStockProduct)
    {
        return $this->getDependencyContainer()->getWriterModel()->createStockProduct($transferStockProduct);
    }

    /**
     * @param StockProductTransfer $stockProductTransfer
     *
     * @return int
     */
    public function updateStockProduct(StockProductTransfer $stockProductTransfer)
    {
        return $this->getDependencyContainer()->getWriterModel()->updateStockProduct($stockProductTransfer);
    }

    /**
     * @param string $sku
     * @param int $decrementBy
     * @param string $stockType
     */
    public function decrementStockProduct($sku, $stockType, $decrementBy = 1)
    {
        $this->getDependencyContainer()->getWriterModel()->decrementStock($sku, $stockType, $decrementBy);
    }

    /**
     * @param string $sku
     * @param int $incrementBy
     * @param string $stockType
     */
    public function incrementStockProduct($sku, $stockType, $incrementBy = 1)
    {
        $this->getDependencyContainer()->getWriterModel()->incrementStock($sku, $stockType, $incrementBy);
    }

    /**
     * @param string $sku
     * @param string $stockType
     *
     * @return bool
     */
    public function hasStockProduct($sku, $stockType)
    {
        return $this->getDependencyContainer()->getReaderModel()->hasStockProduct($sku, $stockType);
    }

    /**
     * @param string $sku
     * @param string $stockType
     *
     * @return int
     */
    public function getIdStockProduct($sku, $stockType)
    {
        return $this->getDependencyContainer()->getReaderModel()->getIdStockProduct($sku, $stockType);
    }

}
