<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\StockTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Stock\Business\StockFacadeInterface getFacade()
 * @method \Spryker\Zed\Stock\Communication\StockCommunicationFactory getFactory()
 * @method \Spryker\Zed\Stock\StockConfig getConfig()
 * @method \Spryker\Zed\Stock\Business\StockBusinessFactory getBusinessFactory()
 */
class PostUpdateStockRelationsEventListener extends AbstractPlugin implements EventHandlerInterface
{
    /**
     * Specification:
     * - Handles the `Stock.stock.post_update_stock_relations` event.
     * - Executes `\Spryker\Zed\StockExtension\Dependency\Plugin\StockUpdateHandlerPluginInterface` plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer $eventEntityTransfer
     * @param string $eventName
     *
     * @return void
     */
    public function handle(TransferInterface $eventEntityTransfer, $eventName): void
    {
        if (!$eventEntityTransfer->getId()) {
            return;
        }

        $stockTransfer = (new StockTransfer())
            ->setIdStock($eventEntityTransfer->getId());

        $this->getBusinessFactory()
            ->createStockProductUpdater()
            ->updateStockProductsRelatedToStock($stockTransfer);
    }
}
