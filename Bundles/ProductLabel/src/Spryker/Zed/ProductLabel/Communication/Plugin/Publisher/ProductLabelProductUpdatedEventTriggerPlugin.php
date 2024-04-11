<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Communication\Plugin\Publisher;

use Spryker\Zed\ProductLabel\Dependency\ProductLabelEvents;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\ProductLabel\ProductLabelConfig getConfig()
 * @method \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface getFacade()
 */
class ProductLabelProductUpdatedEventTriggerPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Triggers product abstract update events.
     * - Events contains product abstract IDs.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName)
    {
        $this->getFacade()->triggerProductAbstractUpdateEventsByProductLabelEvents($eventEntityTransfers);
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
            ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_UPDATE,
            ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_LOCALIZED_ATTRIBUTE_CREATE,
            ProductLabelEvents::ENTITY_SPY_PRODUCT_LABEL_LOCALIZED_ATTRIBUTE_UPDATE,
        ];
    }
}
