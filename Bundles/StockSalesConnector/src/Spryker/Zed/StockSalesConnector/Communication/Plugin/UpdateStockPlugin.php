<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockSalesConnector\Communication\Plugin;

use Generated\Shared\Transfer\StockProductTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\StockSalesConnector\Communication\StockSalesConnectorCommunicationFactory getFactory()
 */
class UpdateStockPlugin extends AbstractPlugin
{
    // TODO not sure this Connector/Plugin will be needed after refactor sales Bundle!

    /**
     * @api
     *
     * @param string $sku
     * @param string $stockType
     * @param int $incrementBy
     *
     * @return void
     */
    public function incrementStockProduct($sku, $stockType, $incrementBy = 1)
    {
        $this->getFactory()->getStockFacade()->incrementStockProduct($sku, $stockType, $incrementBy);
    }

    /**
     * @api
     *
     * @param string $sku
     * @param string $stockType
     * @param int $decrementBy
     *
     * @return void
     */
    public function decrementStockProduct($sku, $stockType, $decrementBy = 1)
    {
        $this->getFactory()->getStockFacade()->decrementStockProduct($sku, $stockType, $decrementBy);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\StockProductTransfer $transferStockProduct
     *
     * @return int
     */
    public function updateStockProduct(StockProductTransfer $transferStockProduct)
    {
        return $this->getFactory()->getStockFacade()->updateStockProduct($transferStockProduct);
    }
}
