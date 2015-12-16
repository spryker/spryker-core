<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\StockSalesConnector\Communication\Plugin;

use Generated\Shared\Transfer\StockProductTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StockSalesConnector\Business\StockSalesConnectorDependencyContainer;

/**
 * @method StockSalesConnectorDependencyContainer getCommunicationFactory()
 */
class UpdateStockPlugin extends AbstractPlugin
{

    // TODO not sure this Connector/Plugin will be needed after refactor sales Bundle!

    /**
     * @param string $sku
     * @param string $stockType
     * @param int $incrementBy
     *
     * @return void
     */
    public function incrementStockProduct($sku, $stockType, $incrementBy = 1)
    {
        $this->getCommunicationFactory()->getStockFacade()->incrementStockProduct($sku, $stockType, $incrementBy);
    }

    /**
     * @param string $sku
     * @param string $stockType
     * @param int $decrementBy
     *
     * @return void
     */
    public function decrementStockProduct($sku, $stockType, $decrementBy = 1)
    {
        $this->getCommunicationFactory()->getStockFacade()->decrementStockProduct($sku, $stockType, $decrementBy);
    }

    /**
     * @param StockProductTransfer $transferStockProduct
     *
     * @return int
     */
    public function updateStockProduct(StockProductTransfer $transferStockProduct)
    {
        return $this->getCommunicationFactory()->getStockFacade()->updateStockProduct($transferStockProduct);
    }

}
