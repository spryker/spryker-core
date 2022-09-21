<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Publisher\Product;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductSearch\Dependency\ProductSearchEvents;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 */
class ProductConcretePageSearchWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Extracts product search IDs from the $eventTransfers.
     * - Finds all product IDs related to product search IDs.
     * - Finds concrete products by product IDs.
     * - Executes a stack of {@link \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductConcreteCollectionFilterPluginInterface} plugins.
     * - Publishes searchable concrete products.
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
        $this->getFacade()->writeProductConcretePageSearchCollectionByProductEvents($eventEntityTransfers);
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
            ProductSearchEvents::ENTITY_SPY_PRODUCT_SEARCH_CREATE,
            ProductSearchEvents::ENTITY_SPY_PRODUCT_SEARCH_UPDATE,
        ];
    }
}
