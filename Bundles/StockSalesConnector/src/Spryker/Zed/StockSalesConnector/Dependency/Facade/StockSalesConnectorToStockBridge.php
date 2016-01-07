<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\StockSalesConnector\Dependency\Facade;

use Spryker\Zed\Stock\Business\StockFacade;
use Generated\Shared\Transfer\StockProductTransfer;

class StockSalesConnectorToStockBridge implements StockSalesConnectorToStockInterface
{

    /**
     * @var StockFacade
     */
    protected $stockFacade;

    /**
     * StockToSalesBridge constructor.
     *
     * @param StockFacade $stockFacade
     */
    public function __construct($stockFacade)
    {
        $this->stockFacade = $stockFacade;
    }

    /**
     * @param StockProductTransfer $transferStockProduct
     *
     * @return int
     */
    public function updateStockProduct(StockProductTransfer $transferStockProduct)
    {
        return $this->stockFacade->updateStockProduct($transferStockProduct);
    }

    /**
     * @param string $sku
     * @param int $decrementBy
     * @param string $stockType
     */
    public function decrementStockProduct($sku, $stockType, $decrementBy = 1)
    {
        $this->stockFacade->decrementStockProduct($sku, $stockType, $decrementBy);
    }

    /**
     * @param string $sku
     * @param string $stockType
     * @param int $incrementBy
     */
    public function incrementStockProduct($sku, $stockType, $incrementBy = 1)
    {
        $this->stockFacade->incrementStockProduct($sku, $stockType, $incrementBy);
    }

}
