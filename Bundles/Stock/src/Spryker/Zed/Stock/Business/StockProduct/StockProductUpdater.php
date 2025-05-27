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
     * @var int
     */
    protected const STOCK_PRODUCT_BATCH_SIZE = 200;

    /**
     * @var \Spryker\Zed\Stock\Persistence\StockRepositoryInterface
     */
    protected $stockRepository;

    /**
     * @var array<\Spryker\Zed\StockExtension\Dependency\Plugin\StockUpdateHandlerPluginInterface>
     */
    protected $stockUpdateHandlerPlugins;

    /**
     * @param \Spryker\Zed\Stock\Persistence\StockRepositoryInterface $stockRepository
     * @param array<\Spryker\Zed\StockExtension\Dependency\Plugin\StockUpdateHandlerPluginInterface> $stockUpdateHandlerPlugins
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
        $offset = 0;

        do {
            $stockProductTransfers = $this->stockRepository->getStockProductsByIdStock($stockTransfer->getIdStockOrFail(), $offset, static::STOCK_PRODUCT_BATCH_SIZE);

            if (count($stockProductTransfers) === 0) {
                break;
            }

            foreach ($stockProductTransfers as $stockProductTransfer) {
                $this->handleStockUpdatePlugins($stockProductTransfer->getSkuOrFail());
            }

            $offset += static::STOCK_PRODUCT_BATCH_SIZE;
        } while (count($stockProductTransfers) === static::STOCK_PRODUCT_BATCH_SIZE);
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
