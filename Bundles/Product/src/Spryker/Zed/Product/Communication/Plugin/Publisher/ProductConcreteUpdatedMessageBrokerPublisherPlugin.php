<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\ProductPublisherConfigTransfer;
use Generated\Shared\Transfer\ProductUpdatedTransfer;
use Spryker\Shared\Product\ProductConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\Product\Business\ProductFacadeInterface getFacade()
 * @method \Spryker\Zed\Product\ProductConfig getConfig()
 * @method \Spryker\Zed\Product\Communication\ProductCommunicationFactory getFactory()
 * @method \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface getQueryContainer()
 */
class ProductConcreteUpdatedMessageBrokerPublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
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
        $productPublisherConfigTransfer = $this->getProductPublisherConfigTransfer($eventEntityTransfers);

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

        return $this->getConfig()->getProductUpdateMessageBrokerPublisherSubscribedEvents();
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return \Generated\Shared\Transfer\ProductPublisherConfigTransfer
     */
    protected function getProductPublisherConfigTransfer(array $eventEntityTransfers): ProductPublisherConfigTransfer
    {
        $productIds = [];
        $productAbstractIds = [];

        foreach ($eventEntityTransfers as $eventEntityTransfer) {
            if ($eventEntityTransfer->getId() !== null) {
                $productIds[] = $eventEntityTransfer->getId();

                continue;
            }

            $fkProductAbstract = $eventEntityTransfer->getForeignKeys()[ProductConfig::COLUMN_FK_PRODUCT_ABSTRACT] ?? null;

            if ($fkProductAbstract !== null) {
                $productAbstractIds[] = $fkProductAbstract;
            }
        }

        return (new ProductPublisherConfigTransfer())
            ->setProductIds(array_unique($productIds))
            ->setProductAbstractIds(array_unique($productAbstractIds))
            ->setEventName(ProductUpdatedTransfer::class);
    }
}
