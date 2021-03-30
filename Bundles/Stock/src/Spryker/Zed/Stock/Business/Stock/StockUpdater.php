<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business\Stock;

use Generated\Shared\Transfer\StockResponseTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\Stock\Business\StockProduct\StockProductUpdaterInterface;
use Spryker\Zed\Stock\Dependency\External\StockToConnectionInterface;
use Spryker\Zed\Stock\Dependency\Facade\StockToTouchInterface;
use Spryker\Zed\Stock\Persistence\StockEntityManagerInterface;
use Throwable;

class StockUpdater implements StockUpdaterInterface
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
     * @var \Spryker\Zed\Stock\Business\Stock\StockStoreRelationshipUpdaterInterface
     */
    protected $stockStoreRelationshipUpdater;

    /**
     * @var \Spryker\Zed\Stock\Business\StockProduct\StockProductUpdaterInterface
     */
    protected $stockProductUpdater;

    /**
     * @var \Spryker\Zed\Stock\Dependency\External\StockToConnectionInterface
     */
    protected $connection;

    /**
     * @var \Spryker\Zed\StockExtension\Dependency\Plugin\StockPostUpdatePluginInterface[]
     */
    protected $stockPostUpdatePlugins;

    /**
     * @param \Spryker\Zed\Stock\Persistence\StockEntityManagerInterface $stockEntityManager
     * @param \Spryker\Zed\Stock\Dependency\Facade\StockToTouchInterface $touchFacade
     * @param \Spryker\Zed\Stock\Business\Stock\StockStoreRelationshipUpdaterInterface $stockStoreRelationshipUpdater
     * @param \Spryker\Zed\Stock\Business\StockProduct\StockProductUpdaterInterface $stockProductUpdater
     * @param \Spryker\Zed\Stock\Dependency\External\StockToConnectionInterface $connection
     * @param \Spryker\Zed\StockExtension\Dependency\Plugin\StockPostUpdatePluginInterface[] $stockPostUpdatePlugins
     */
    public function __construct(
        StockEntityManagerInterface $stockEntityManager,
        StockToTouchInterface $touchFacade,
        StockStoreRelationshipUpdaterInterface $stockStoreRelationshipUpdater,
        StockProductUpdaterInterface $stockProductUpdater,
        StockToConnectionInterface $connection,
        array $stockPostUpdatePlugins
    ) {
        $this->stockEntityManager = $stockEntityManager;
        $this->touchFacade = $touchFacade;
        $this->stockStoreRelationshipUpdater = $stockStoreRelationshipUpdater;
        $this->connection = $connection;
        $this->stockProductUpdater = $stockProductUpdater;
        $this->stockPostUpdatePlugins = $stockPostUpdatePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return \Generated\Shared\Transfer\StockResponseTransfer
     */
    public function updateStock(StockTransfer $stockTransfer): StockResponseTransfer
    {
        $this->connection->beginTransaction();

        try {
            $stockResponseTransfer = $this->executeUpdateStockTransaction($stockTransfer);
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
    protected function executeUpdateStockTransaction(StockTransfer $stockTransfer): StockResponseTransfer
    {
        $stockTransfer->requireIdStock();

        $stockTransfer = $this->stockEntityManager->saveStock($stockTransfer);
        $this->stockStoreRelationshipUpdater->updateStockStoreRelationshipsForStock(
            $stockTransfer->getIdStock(),
            $stockTransfer->getStoreRelation()
        );

        $this->insertActiveTouchRecordStockType($stockTransfer);
        $this->stockProductUpdater->updateStockProductsRelatedToStock($stockTransfer);

        return $this->executeStockPostUpdatePlugins($stockTransfer);
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
    protected function executeStockPostUpdatePlugins(StockTransfer $stockTransfer): StockResponseTransfer
    {
        foreach ($this->stockPostUpdatePlugins as $stockPostUpdatePlugin) {
            $stockResponseTransfer = $stockPostUpdatePlugin->postUpdate($stockTransfer);
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
