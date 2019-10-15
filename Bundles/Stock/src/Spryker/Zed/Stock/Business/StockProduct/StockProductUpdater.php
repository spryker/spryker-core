<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Business\StockProduct;

use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Stock\Persistence\StockRepositoryInterface;

class StockProductUpdater implements StockProductUpdaterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\Stock\Persistence\StockRepositoryInterface
     */
    protected $stockRepository;

    /**
     * @var \Spryker\Zed\Stock\Dependency\Plugin\StockUpdateHandlerPluginInterface[]
     */
    protected $stockUpdateHandlerPlugins;

    /**
     * @param \Spryker\Zed\Stock\Persistence\StockRepositoryInterface $stockRepository
     * @param \Spryker\Zed\Stock\Dependency\Plugin\StockUpdateHandlerPluginInterface[] $stockUpdateHandlerPlugins
     */
    public function __construct(StockRepositoryInterface $stockRepository, array $stockUpdateHandlerPlugins)
    {
        $this->stockRepository = $stockRepository;
        $this->stockUpdateHandlerPlugins = $stockUpdateHandlerPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return void
     */
    public function updateStockProductsRelatedToStock(StockTransfer $stockTransfer): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($stockTransfer): void {
            $this->executeUpdateStockProductsRelatedToStockTransaction($stockTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\StockTransfer $stockTransfer
     *
     * @return void
     */
    protected function executeUpdateStockProductsRelatedToStockTransaction(StockTransfer $stockTransfer): void
    {
        $stockProducts = $this->stockRepository->getStockProductsByIdStock($stockTransfer->getIdStock());
        foreach ($stockProducts as $stockProductTransfer) {
            $this->handleStockUpdatePlugins($stockProductTransfer->getSku());
        }
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    protected function handleStockUpdatePlugins(string $sku): void
    {
        foreach ($this->stockUpdateHandlerPlugins as $stockUpdateHandlerPlugin) {
            $stockUpdateHandlerPlugin->handle($sku);
        }
    }
}
