<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockAddress\Business\Updater;

use Generated\Shared\Transfer\StockResponseTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\StockAddress\Business\Deleter\StockAddressDeleterInterface;
use Spryker\Zed\StockAddress\Persistence\StockAddressEntityManagerInterface;
use Spryker\Zed\StockAddress\Persistence\StockAddressRepositoryInterface;

class StockAddressUpdater implements StockAddressUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\StockAddress\Persistence\StockAddressEntityManagerInterface
     */
    protected $stockAddressEntityManager;

    /**
     * @var \Spryker\Zed\StockAddress\Persistence\StockAddressRepositoryInterface
     */
    protected $stockAddressRepository;

    /**
     * @var \Spryker\Zed\StockAddress\Business\Deleter\StockAddressDeleterInterface
     */
    protected $stockAddressDeleter;

    /**
     * @param \Spryker\Zed\StockAddress\Persistence\StockAddressEntityManagerInterface $stockAddressEntityManager
     * @param \Spryker\Zed\StockAddress\Persistence\StockAddressRepositoryInterface $stockAddressRepository
     * @param \Spryker\Zed\StockAddress\Business\Deleter\StockAddressDeleterInterface $stockAddressDeleter
     */
    public function __construct(
        StockAddressEntityManagerInterface $stockAddressEntityManager,
        StockAddressRepositoryInterface $stockAddressRepository,
        StockAddressDeleterInterface $stockAddressDeleter
    ) {
        $this->stockAddressEntityManager = $stockAddressEntityManager;
        $this->stockAddressRepository = $stockAddressRepository;
        $this->stockAddressDeleter = $stockAddressDeleter;
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockResponseTransfer
     */
    public function updateStockAddressForStock(StockTransfer $stockTransfer): StockResponseTransfer
    {
        if (!$stockTransfer->getAddress()) {
            if (!$this->stockAddressRepository->isStockAddressExistsForStock($stockTransfer->getIdStockOrFail())) {
                return (new StockResponseTransfer())
                    ->setStock($stockTransfer)
                    ->setIsSuccessful(true);
            }

            return $this->stockAddressDeleter->deleteStockAddressForStock($stockTransfer);
        }

        return $this->getTransactionHandler()->handleTransaction(function () use ($stockTransfer) {
            return $this->executeUpdateStockAddressForStockTransaction($stockTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockResponseTransfer
     */
    protected function executeUpdateStockAddressForStockTransaction(StockTransfer $stockTransfer): StockResponseTransfer
    {
        $stockAddressTransfer = $this->stockAddressEntityManager->saveStockAddress($stockTransfer->getAddressOrFail());
        $stockTransfer->setAddress($stockAddressTransfer);

        return (new StockResponseTransfer())
            ->setStock($stockTransfer)
            ->setIsSuccessful(true);
    }
}
