<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Communication\Plugin\Publisher\ProductLabel;

use Spryker\Shared\ProductLabelSearch\ProductLabelSearchConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\ProductLabelSearch\Communication\ProductLabelSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductLabelSearch\Business\ProductLabelSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductLabelSearch\Persistence\ProductLabelSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductLabelSearch\ProductLabelSearchConfig getConfig()
 */
class ProductLabelWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Publishes product label data by create and delete events from product label table.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $this->getFacade()->writeCollectionByProductLabelEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string[]
     */
    public function getSubscribedEvents(): array
    {
        return [
            ProductLabelSearchConfig::ENTITY_SPY_PRODUCT_LABEL_UPDATE,
            ProductLabelSearchConfig::ENTITY_SPY_PRODUCT_LABEL_DELETE,
        ];
    }
}
