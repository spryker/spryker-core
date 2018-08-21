<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageResourceAliasStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductImageResourceAliasStorage\Business\ProductImageResourceAliasStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductImageResourceAliasStorage\Communication\ProductImageResourceAliasStorageCommunicationFactory getFactory()
 */
class ProductAbstractImageMappingResourceStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        $productAbstractImagesIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($eventTransfers);
        $this->getFacade()->updateProductAbstractImageStorageSkus($productAbstractImagesIds);
    }
}
