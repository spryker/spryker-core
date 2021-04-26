<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business\Stock;

use Generated\Shared\Transfer\StockResponseTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\Stock\Dependency\External\StockToConnectionInterface;
use Spryker\Zed\Stock\Dependency\Facade\StockToTouchInterface;
use Spryker\Zed\Stock\Persistence\StockEntityManagerInterface;
use Throwable;

class StockCreator implements StockCreatorInterface
{
    protected const TOUCH_STOCK_TYPE = 'stock-type';

    /**
     * @var \Spryker\Zed\Stock\Persistence\StockEntityManagerInterface
     */
    protected $stockEntityManager;

    /**
     * @var \Spryker\Zed\Stock\Dependency\Facade\StockToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var \Spryker\Zed\Stock\Dependency\External\StockToConnectionInterface
     */
    protected $connection;

    /**
     * @var \Spryker\Zed\StockExtension\Dependency\Plugin\StockPostCreatePluginInterface[]
     */
    protected $stockPostCreatePlugins;

    /**
     * @param \Spryker\Zed\Stock\Persistence\StockEntityManagerInterface $stockEntityManager
     * @param \Spryker\Zed\Stock\Dependency\Facade\StockToTouchInterface $touchFacade
     * @param \Spryker\Zed\Stock\Dependency\External\StockToConnectionInterface $connection
     * @param \Spryker\Zed\StockExtension\Dependency\Plugin\StockPostCreatePluginInterface[] $stockPostCreatePlugins
     */
    public function __construct(
        StockEntityManagerInterface $stockEntityManager,
        StockToTouchInterface $touchFacade,
        StockToConnectionInterface $connection,
        array $stockPostCreatePlugins
    ) {
        $this->stockEntityManager = $stockEntityManager;
        $this->touchFacade = $touchFacade;
        $this->connection = $connection;
        $this->stockPostCreatePlugins = $stockPostCreatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @throws \Throwable
     *
     * @return \Generated\Shared\Transfer\StockResponseTransfer
     */
    public function createStock(StockTransfer $stockTransfer): StockResponseTransfer
    {
        $this->connection->beginTransaction();

        try {
            $stockResponseTransfer = $this->executeCreateStockTransaction($stockTransfer);
            if (!$stockResponseTransfer->getIsSuccessful()) {
                $this->connection->rollBack();

                return $stockResponseTransfer;
            }

            $this->connection->commit();

            return $stockResponseTransfer;
        } catch (Throwable $exception) {
            $this->connection->rollBack();

            throw $exception;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockResponseTransfer
     */
    protected function executeCreateStockTransaction(StockTransfer $stockTransfer): StockResponseTransfer
    {
        $stockTransfer = $this->stockEntityManager->saveStock($stockTransfer);
        if ($stockTransfer->getStoreRelation() !== null) {
            $this->stockEntityManager->addStockStoreRelations(
                $stockTransfer->getIdStock(),
                $stockTransfer->getStoreRelation()->getIdStores()
            );
        }

        $this->insertActiveTouchRecordStockType($stockTransfer);

        return $this->executeStockPostCreatePlugins($stockTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return void
     */
    protected function insertActiveTouchRecordStockType(StockTransfer $stockTransfer): void
    {
        $this->touchFacade->touchActive(
            static::TOUCH_STOCK_TYPE,
            $stockTransfer->getIdStock()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockResponseTransfer
     */
    protected function executeStockPostCreatePlugins(StockTransfer $stockTransfer): StockResponseTransfer
    {
        foreach ($this->stockPostCreatePlugins as $stockPostCreatePlugin) {
            $stockResponseTransfer = $stockPostCreatePlugin->postCreate($stockTransfer);
            if (!$stockResponseTransfer->getIsSuccessful()) {
                return $stockResponseTransfer;
            }

            $stockTransfer = $stockResponseTransfer->getStock();
        }

        return (new StockResponseTransfer())
            ->setStock($stockTransfer)
            ->setIsSuccessful(true);
    }
}
