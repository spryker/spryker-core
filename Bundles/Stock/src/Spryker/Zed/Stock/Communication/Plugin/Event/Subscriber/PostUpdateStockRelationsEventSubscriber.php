<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Stock\Communication\Plugin\Event\Subscriber;

use Spryker\Shared\Stock\StockConfig as SharedStockConfig;
use Spryker\Zed\Event\Dependency\EventCollectionInterface;
use Spryker\Zed\Event\Dependency\Plugin\EventSubscriberInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Stock\Communication\Plugin\Event\Listener\PostUpdateStockRelationsEventListener;

/**
 * @method \Spryker\Zed\Stock\StockConfig getConfig()
 * @method \Spryker\Zed\Stock\Business\StockFacadeInterface getFacade()
 * @method \Spryker\Zed\Stock\Communication\StockCommunicationFactory getFactory()
 */
class PostUpdateStockRelationsEventSubscriber extends AbstractPlugin implements EventSubscriberInterface
{
    /**
     * Specification:
     * - Subscribes to the `Stock.stock.post_update_stock_relations` event.
     * - Used for asynchronous processing of stock relations after a stock update for performance optimization.
     * - Impacts `StockFacade::updateStock()` method with enabled `StockTransfer::shouldUpdateStockRelationsAsync()` flag.
     *
     * @api
     *
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return \Spryker\Zed\Event\Dependency\EventCollectionInterface
     */
    public function getSubscribedEvents(EventCollectionInterface $eventCollection): EventCollectionInterface
    {
        $this->addPostUpdateStockRelationsListener($eventCollection);

        return $eventCollection;
    }

    /**
     * @param \Spryker\Zed\Event\Dependency\EventCollectionInterface $eventCollection
     *
     * @return void
     */
    protected function addPostUpdateStockRelationsListener(EventCollectionInterface $eventCollection): void
    {
        $eventCollection->addListenerQueued(
            SharedStockConfig::STOCK_POST_UPDATE_STOCK_RELATIONS,
            new PostUpdateStockRelationsEventListener(),
            0,
            null,
            $this->getConfig()->getEventQueueName(),
        );
    }
}
