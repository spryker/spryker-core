<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductImageStorage\Communication\ProductImageStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductImageStorage\Business\ProductImageStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductImageStorage\ProductImageStorageConfig getConfig()
 */
class ProductAbstractImageSetProductImageStorageUnpublishListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * {@inheritDoc}
     * - Removes and updates product abstract image data by `SpyProductImageSetToProductImage` entity events.
     * - Extracts product image set IDs from the `$eventEntityTransfers` created by product image set to product image entity events.
     * - Finds product abstract IDs by product image set IDs.
     * - Collects product abstract image data.
     * - Updates product abstract image storage entities with modified image data.
     * - Finds and deletes product abstract image storage entities without image data.
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
        $this->getFacade()->deleteProductAbstractImageStorageCollectionByProductImageSetToProductImageEvents($eventEntityTransfers);
    }
}
