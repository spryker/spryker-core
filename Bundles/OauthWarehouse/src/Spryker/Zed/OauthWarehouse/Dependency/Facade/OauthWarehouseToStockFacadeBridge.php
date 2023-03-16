<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthWarehouse\Dependency\Facade;

use Generated\Shared\Transfer\StockCollectionTransfer;
use Generated\Shared\Transfer\StockCriteriaFilterTransfer;

class OauthWarehouseToStockFacadeBridge implements OauthWarehouseToStockFacadeInterface
{
    /**
     * @var \Spryker\Zed\Stock\Business\StockFacadeInterface
     */
    protected $stockFacade;

    /**
     * @param \Spryker\Zed\Stock\Business\StockFacadeInterface $stockFacade
     */
    public function __construct($stockFacade)
    {
        $this->stockFacade = $stockFacade;
    }

    /**
     * @deprecated Use the FETCH RFC compliant method instead.
     *
     * @param \Generated\Shared\Transfer\StockCriteriaFilterTransfer $stockCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\StockCollectionTransfer
     */
    public function getStocksByStockCriteriaFilter(StockCriteriaFilterTransfer $stockCriteriaFilterTransfer): StockCollectionTransfer
    {
        return $this->stockFacade->getStocksByStockCriteriaFilter($stockCriteriaFilterTransfer);
    }
}
