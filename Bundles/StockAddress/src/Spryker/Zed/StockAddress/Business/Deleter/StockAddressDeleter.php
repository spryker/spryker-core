<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockAddress\Business\Deleter;

use Generated\Shared\Transfer\StockResponseTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\StockAddress\Persistence\StockAddressEntityManagerInterface;

class StockAddressDeleter implements StockAddressDeleterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\StockAddress\Persistence\StockAddressEntityManagerInterface
     */
    protected $stockAddressEntityManager;

    /**
     * @param \Spryker\Zed\StockAddress\Persistence\StockAddressEntityManagerInterface $stockAddressEntityManager
     */
    public function __construct(StockAddressEntityManagerInterface $stockAddressEntityManager)
    {
        $this->stockAddressEntityManager = $stockAddressEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockResponseTransfer
     */
    public function deleteStockAddressForStock(StockTransfer $stockTransfer): StockResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($stockTransfer) {
            return $this->executeDeleteStockAddressForStockTransaction($stockTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockResponseTransfer
     */
    protected function executeDeleteStockAddressForStockTransaction(StockTransfer $stockTransfer): StockResponseTransfer
    {
        $this->stockAddressEntityManager->deleteStockAddressForStock($stockTransfer->getIdStockOrFail());

        return (new StockResponseTransfer())
            ->setStock($stockTransfer->setAddress(null))
            ->setIsSuccessful(true);
    }
}
