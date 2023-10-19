<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Publisher\CategoryStore;

use Spryker\Shared\ProductPageSearch\ProductPageSearchConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 */
class CategoryStoreProductAbstractPageSearchWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Publishes product abstract page data by `SpyCategoryStore` entity events.
     * - Extracts category IDs from the `$eventEntityTransfers` created by category store events.
     * - Finds all category IDs related to the category IDs from the `$eventTransfers`.
     * - Finds abstract products by category IDs.
     * - Executes a stack of {@link \Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductPageSearchCollectionFilterPluginInterface} plugins.
     * - Stores data in storage table.
     * - Sends a copy of data to the queue.
     *
     * @api
     *
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $this->getFacade()->writeProductAbstractPageSearchCollectionByCategoryStoreEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return list<string>
     */
    public function getSubscribedEvents(): array
    {
        return [
            ProductPageSearchConfig::ENTITY_SPY_CATEGORY_STORE_CREATE,
            ProductPageSearchConfig::ENTITY_SPY_CATEGORY_STORE_UPDATE,
            ProductPageSearchConfig::ENTITY_SPY_CATEGORY_STORE_DELETE,
        ];
    }
}
