<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;

/**
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface getRepository()
 */
class ProductPageImageSetProductImageSearchListener extends AbstractProductPageSearchListener implements EventBulkHandlerInterface
{
    /**
     * {@inheritDoc}
     * - Publishes product abstract page data by `SpyProductImageSetToProductImage` entity events.
     * - Extracts product image set IDs from the `$eventEntityTransfers` created by product image set to product image entity events.
     * - Finds product abstract IDs by product image set IDs.
     * - Collects product abstract page data.
     * - Stores data in search table.
     * - Sends a copy of data to the queue.
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
        $this->getFacade()->writeProductAbstractPageSearchCollectionByProductImageSetToProductImageEvents($eventEntityTransfers);
    }
}
