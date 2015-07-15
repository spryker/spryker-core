<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\StockSalesConnector\Communication\Plugin;

use Generated\Shared\Transfer\StockProductTransfer;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\StockSalesConnector\Business\StockSalesConnectorDependencyContainer;

/**
 * @method StockSalesConnectorDependencyContainer getDependencyContainer()
 */
class UpdateStockPlugin extends AbstractPlugin
{

    // TODO not sure this Connector/Plugin will be needed after refactor sales Bundle!

    /**
     * @param string $sku
     * @param string $stockType
     * @param int $incrementBy
     */
    public function incrementStockProduct($sku, $stockType, $incrementBy = 1)
    {
        $this->getDependencyContainer()->getStockFacade()->incrementStockProduct($sku, $stockType, $incrementBy);
    }

    /**
     * @param string $sku
     * @param string $stockType
     * @param int $decrementBy
     */
    public function decrementStockProduct($sku, $stockType, $decrementBy = 1)
    {
        $this->getDependencyContainer()->getStockFacade()->decrementStockProduct($sku, $stockType, $decrementBy);
    }

    /**
     * @param StockProductTransfer $transferStockProduct
     *
     * @return int
     */
    public function updateStockProduct(StockProductTransfer $transferStockProduct)
    {
        return $this->getDependencyContainer()->getStockFacade()->updateStockProduct($transferStockProduct);
    }

}
