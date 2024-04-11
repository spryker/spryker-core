<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Communication\Plugin\Publisher;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\ProductCategory\ProductCategoryConfig getConfig()
 * @method \Spryker\Zed\ProductCategory\Business\ProductCategoryFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductCategory\Communication\ProductCategoryCommunicationFactory getFactory()
 */
class ProductCategoryProductUpdatedEventTriggerPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Triggers product update events when category entities are updated.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $this->getFacade()->triggerProductAbstractUpdateEventsByCategoryEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string>
     */
    public function getSubscribedEvents(): array
    {
        return [
            ProductCategoryEvents::ENTITY_SPY_CATEGORY_UPDATE,
            ProductCategoryEvents::ENTITY_SPY_CATEGORY_NODE_CREATE,
            ProductCategoryEvents::ENTITY_SPY_CATEGORY_NODE_UPDATE,
            ProductCategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_CREATE,
            ProductCategoryEvents::ENTITY_SPY_CATEGORY_ATTRIBUTE_UPDATE,
        ];
    }
}
