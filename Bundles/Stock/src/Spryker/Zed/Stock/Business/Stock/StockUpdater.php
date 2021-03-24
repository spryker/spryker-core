<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business\Stock;

use Generated\Shared\Transfer\StockResponseTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Stock\Business\Exception\StockNotSavedException;
use Spryker\Zed\Stock\Business\StockProduct\StockProductUpdaterInterface;
use Spryker\Zed\Stock\Dependency\Facade\StockToTouchInterface;
use Spryker\Zed\Stock\Persistence\StockEntityManagerInterface;

class StockUpdater implements StockUpdaterInterface
{
    use TransactionTrait;

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
     * @var \Spryker\Zed\StockExtension\Dependency\Plugin\StockPostUpdatePluginInterface[]
     */
    protected $stockPostUpdatePlugins;

    /**
     * @param \Spryker\Zed\Stock\Persistence\StockEntityManagerInterface $stockEntityManager
     * @param \Spryker\Zed\Stock\Dependency\Facade\StockToTouchInterface $touchFacade
     * @param \Spryker\Zed\Stock\Business\Stock\StockStoreRelationshipUpdaterInterface $stockStoreRelationshipUpdater
     * @param \Spryker\Zed\Stock\Business\StockProduct\StockProductUpdaterInterface $stockProductUpdater
     * @param \Spryker\Zed\StockExtension\Dependency\Plugin\StockPostUpdatePluginInterface[] $stockPostUpdatePlugins
     */
    public function __construct(
        StockEntityManagerInterface $stockEntityManager,
        StockToTouchInterface $touchFacade,
        StockStoreRelationshipUpdaterInterface $stockStoreRelationshipUpdater,
        StockProductUpdaterInterface $stockProductUpdater,
        array $stockPostUpdatePlugins
    ) {
        $this->stockEntityManager = $stockEntityManager;
        $this->touchFacade = $touchFacade;
        $this->stockStoreRelationshipUpdater = $stockStoreRelationshipUpdater;
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
        return $this->getTransactionHandler()->handleTransaction(function () use ($stockTransfer): StockResponseTransfer {
            return $this->executeUpdateStockTransaction($stockTransfer);
        });
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
     * @throws \Spryker\Zed\Stock\Business\Exception\StockNotSavedException
     *
     * @return \Generated\Shared\Transfer\StockResponseTransfer
     */
    protected function executeStockPostUpdatePlugins(StockTransfer $stockTransfer): StockResponseTransfer
    {
        foreach ($this->stockPostUpdatePlugins as $stockPostUpdatePlugin) {
            $stockResponseTransfer = $stockPostUpdatePlugin->postUpdate($stockTransfer);
            if (!$stockResponseTransfer->getIsSuccessful()) {
                throw new StockNotSavedException($stockResponseTransfer->getMessages());
            }

            $stockTransfer = $stockResponseTransfer->getStock();
        }

        return (new StockResponseTransfer())
            ->setStock($stockTransfer)
            ->setIsSuccessful(true);
    }
}
