<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductResourceAliasStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductResourceAliasStorage\Business\ProductResourceAliasStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductResourceAliasStorage\Communication\ProductResourceAliasStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductResourceAliasStorage\ProductResourceAliasStorageConfig getConfig()
 */
class ProductConcreteMappingResourceStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        $productConcreteIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($eventTransfers);

        $this->getFacade()->updateProductConcreteStorageSkus($productConcreteIds);
    }
}
