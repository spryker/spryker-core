<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\ProductExportedTransfer;
use Spryker\Zed\Product\Dependency\ProductEvents;

/**
 * @method \Spryker\Zed\Product\Business\ProductFacadeInterface getFacade()
 * @method \Spryker\Zed\Product\ProductConfig getConfig()
 * @method \Spryker\Zed\Product\Communication\ProductCommunicationFactory getFactory()
 * @method \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface getQueryContainer()
 */
class ProductConcreteExportedMessageBrokerPublisherPlugin extends AbstractProductMessageBrokerPublisherPlugin
{
    /**
     * {@inheritDoc}
     * - Emits publish product event to message broker.
     * - Event contains product IDs.
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
        $productPublisherConfigTransfer = $this->getProductPublisherConfigTransfer(
            $eventEntityTransfers,
            ProductExportedTransfer::class,
        );

        $this->getFacade()->emitPublishProductToMessageBroker($productPublisherConfigTransfer);
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
        if (!$this->getConfig()->isPublishingToMessageBrokerEnabled()) {
            return [];
        }

        return [
            ProductEvents::PRODUCT_CONCRETE_EXPORT,
        ];
    }
}
