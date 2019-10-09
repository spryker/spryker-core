<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockGui\Dependency\Facade;

use Generated\Shared\Transfer\StockResponseTransfer;
use Generated\Shared\Transfer\StockTransfer;

class StockGuiToStockFacadeBridge implements StockGuiToStockFacadeInterface
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
     * @return array
     */
    public function getWarehouseToStoreMapping()
    {
        return $this->stockFacade->getWarehouseToStoreMapping();
    }

    /**
     * @return array
     */
    public function getStoreToWarehouseMapping()
    {
        return $this->stockFacade->getStoreToWarehouseMapping();
    }

    /**
     * @param int $idStock
     *
     * @return \Generated\Shared\Transfer\StockTransfer|null
     */
    public function findStockById(int $idStock): ?StockTransfer
    {
        return $this->stockFacade->findStockById($idStock);
    }

    /**
     * @param string $stockName
     *
     * @return \Generated\Shared\Transfer\StockTransfer|null
     */
    public function findStockByName(string $stockName): ?StockTransfer
    {
        return $this->stockFacade->findStockByName($stockName);
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockResponseTransfer
     */
    public function createStock(StockTransfer $stockTransfer): StockResponseTransfer
    {
        return $this->stockFacade->createStock($stockTransfer);
    }
}
